<?php

declare(strict_types=1);

use Capell\Core\Facades\CapellCore;
use Capell\Core\ThemeStudio\Theme\ThemeRegistry;
use Capell\LayoutBuilder\Contracts\Extenders\WidgetSchemaExtender;
use Capell\LayoutBuilder\Support\Creator\WidgetCreator;
use Capell\ThemeLiquidGlass\Enums\WidgetComponentEnum;
use Capell\ThemeLiquidGlass\Filament\Extenders\GlassBlockSchemaExtender;
use Capell\ThemeLiquidGlass\LiquidGlassThemeServiceProvider;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;

function bootLiquidGlassForEditingFormTests(): void
{
    CapellCore::forcePackageInstalled('capell-app/theme-foundation');
    CapellCore::forcePackageInstalled(LiquidGlassThemeServiceProvider::$packageName);

    View::addNamespace('capell-theme-liquid-glass', dirname(__DIR__, 2) . '/resources/views');
    Lang::addNamespace('capell-theme-liquid-glass', dirname(__DIR__, 2) . '/resources/lang');
}

/**
 * @return array<string, Section>
 */
function liquidGlassBlockSections(): array
{
    $sections = array_values(array_filter(
        (new GlassBlockSchemaExtender)->extendDisplayComponents(Schema::make(), []),
        static fn (mixed $component): bool => $component instanceof Section,
    ));

    expect($sections)->toHaveCount(count(WidgetComponentEnum::cases()));

    return array_combine(array_map(static fn (WidgetComponentEnum $case): string => $case->value, WidgetComponentEnum::cases()), $sections);
}

/**
 * @return array<int, Component>
 */
function liquidGlassChildFields(Component $component): array
{
    $children = $component->getDefaultChildComponents();

    if ($children instanceof Schema) {
        $children = $children->getComponents();
    }

    return array_values(array_filter($children, static fn (mixed $child): bool => $child instanceof Component));
}

/**
 * Builds the meta an editor would save by filling in every field of a form.
 *
 * @param  array<int, Component>  $fields
 * @return array<string, mixed>
 */
function liquidGlassSampleMeta(array $fields, string $path = ''): array
{
    $meta = [];

    foreach ($fields as $field) {
        if (! $field instanceof Field) {
            continue;
        }

        $name = $field->getName();
        $key = $path === '' ? $name : $path . '.' . $name;

        $meta[$name] = match (true) {
            $field instanceof Repeater && $field->isSimple() => ['Sample ' . $key . ' one', 'Sample ' . $key . ' two'],
            $field instanceof Repeater => [
                liquidGlassSampleMeta(liquidGlassChildFields($field), $key . '.1'),
                liquidGlassSampleMeta(liquidGlassChildFields($field), $key . '.2'),
            ],
            $field instanceof Select => array_key_first($field->getOptions()),
            $field instanceof TextInput && $field->isNumeric() => 7,
            in_array($name, ['image', 'frontImage', 'url'], true) => $name === 'url' ? '/sample-' . $key : '/storage/' . $key . '.jpg',
            $name === 'anchorId' => 'sample-anchor',
            default => 'Sample ' . $key,
        };
    }

    return $meta;
}

it('provides one editing form per Liquid Glass block and tags it as a widget schema extender', function (): void {
    bootLiquidGlassForEditingFormTests();

    $provider = new LiquidGlassThemeServiceProvider(app());
    $provider->register();
    $provider->boot(resolve(ThemeRegistry::class));

    $tagged = iterator_to_array(app()->tagged(WidgetSchemaExtender::TAG), false);

    expect(array_filter($tagged, static fn (object $extender): bool => $extender instanceof GlassBlockSchemaExtender))->toHaveCount(1)
        ->and(GlassBlockSchemaExtender::components())->toEqualCanonicalizing(
            array_map(static fn (WidgetComponentEnum $case): string => $case->value, WidgetComponentEnum::cases()),
        );

    foreach (liquidGlassBlockSections() as $section) {
        expect(liquidGlassChildFields($section))->not->toBeEmpty();
    }

    CapellCore::clearPackages();
});

it('renders every text an editor can enter in each block form', function (WidgetComponentEnum $block): void {
    bootLiquidGlassForEditingFormTests();

    $meta = liquidGlassSampleMeta(liquidGlassChildFields(liquidGlassBlockSections()[$block->value]));

    $widget = resolve(WidgetCreator::class)->bespokeContentWidget(
        key: 'liquid-glass-editing-form-' . $block->name,
        name: 'Editing form ' . $block->name,
        component: $block->value,
        meta: $meta,
    );

    $widgetData = ['widget' => $widget];
    $html = (match ($block) {
        WidgetComponentEnum::Cta => view('capell-theme-liquid-glass::widget.cta', $widgetData),
        WidgetComponentEnum::Showcase => view('capell-theme-liquid-glass::widget.showcase', $widgetData),
        WidgetComponentEnum::Presets => view('capell-theme-liquid-glass::widget.presets', $widgetData),
        WidgetComponentEnum::GlassFeatureCard => view('capell-theme-liquid-glass::widget.glass-feature-card', $widgetData),
        WidgetComponentEnum::TranslucentStatBand => view('capell-theme-liquid-glass::widget.translucent-stat-band', $widgetData),
        WidgetComponentEnum::LayeredDepthHero => view('capell-theme-liquid-glass::widget.layered-depth-hero', $widgetData),
        WidgetComponentEnum::RefractionGrid => view('capell-theme-liquid-glass::widget.refraction-grid', $widgetData),
        WidgetComponentEnum::FloatingGlassNav => view('capell-theme-liquid-glass::widget.floating-glass-nav', $widgetData),
    })->render();

    $samples = [];
    array_walk_recursive($meta, static function (mixed $value) use (&$samples): void {
        if (is_string($value) && str_starts_with($value, 'Sample ')) {
            $samples[] = $value;
        }
    });

    expect($samples)->not->toBeEmpty();

    foreach ($samples as $sample) {
        expect($html)->toContain(e($sample));
    }

    CapellCore::clearPackages();
})->with(WidgetComponentEnum::cases());
