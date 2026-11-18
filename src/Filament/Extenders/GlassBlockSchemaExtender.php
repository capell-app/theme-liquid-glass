<?php

declare(strict_types=1);

namespace Capell\ThemeLiquidGlass\Filament\Extenders;

use Capell\Core\Contracts\Extensions\ExtensionContribution;
use Capell\LayoutBuilder\Contracts\Extenders\WidgetSchemaExtender;
use Capell\LayoutBuilder\Models\Widget;
use Capell\ThemeLiquidGlass\Enums\WidgetComponentEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Override;

/**
 * Editing forms for the eight Liquid Glass blocks.
 *
 * Every field writes the same flat `meta` key the block's Blade view reads
 * with `$widget->getMeta()`, so what an editor enters is exactly what renders.
 * Each block gets its own section, shown only while that block is selected.
 */
final class GlassBlockSchemaExtender implements ExtensionContribution, WidgetSchemaExtender
{
    #[Override]
    public static function compatibleCapellApiVersion(): string
    {
        return '^1.0';
    }

    /**
     * Block component values this extender provides a form for.
     *
     * @return list<string>
     */
    public static function components(): array
    {
        return array_map(static fn (WidgetComponentEnum $case): string => $case->value, WidgetComponentEnum::cases());
    }

    /**
     * @param  array<int, mixed>  $components
     * @return array<int, mixed>
     */
    #[Override]
    public function extendDisplayComponents(Schema $schema, array $components): array
    {
        foreach (WidgetComponentEnum::cases() as $block) {
            $components[] = Section::make(__('capell-theme-liquid-glass::blocks.' . $block->name . '.title'))
                ->description(__('capell-theme-liquid-glass::blocks.' . $block->name . '.description'))
                ->columnSpanFull()
                ->visible(static function (Get $get) use ($schema, $block): bool {
                    $record = $schema->getRecord();

                    return $get('../component') === $block->value
                        || ($record instanceof Widget && $record->getMetaComponent() === $block->value);
                })
                ->schema($this->fieldsFor($block));
        }

        return $components;
    }

    /**
     * @return array<int, Component>
     */
    private function fieldsFor(WidgetComponentEnum $block): array
    {
        return match ($block) {
            WidgetComponentEnum::Cta => [
                ...$this->intro(eyebrow: false),
            ],
            WidgetComponentEnum::Showcase => [
                ...$this->intro(),
                Repeater::make('items')->label($this->label('items'))->defaultItems(0)->maxItems(12)->collapsible()
                    ->itemLabel(static fn (array $state): ?string => $state['title'] ?? null)
                    ->schema([
                        $this->text('discipline'),
                        $this->text('title', required: true),
                        Textarea::make('summary')->label($this->label('summary'))->maxLength(1000),
                        ...$this->image(),
                        $this->text('metric', max: 40),
                        $this->text('metricLabel', max: 80),
                    ]),
            ],
            WidgetComponentEnum::Presets => [
                ...$this->intro(),
                Repeater::make('presets')->label($this->label('presets'))->defaultItems(0)->maxItems(12)->collapsible()
                    ->itemLabel(static fn (array $state): ?string => $state['name'] ?? null)
                    ->schema([
                        $this->text('name', required: true),
                        $this->text('title'),
                        Textarea::make('description')->label($this->label('description'))->maxLength(1000),
                        Repeater::make('surfaces')->label($this->label('surfaces'))->defaultItems(0)->maxItems(12)
                            ->simple(TextInput::make('surface')->label($this->label('surface'))->required()->maxLength(120)),
                    ]),
            ],
            WidgetComponentEnum::GlassFeatureCard => [
                $this->text('anchorId', max: 80, anchor: true),
                ...$this->intro(),
                $this->variant(['standard', 'spotlight'], 'standard'),
                Repeater::make('items')->label($this->label('items'))->defaultItems(0)->maxItems(12)->collapsible()
                    ->itemLabel(static fn (array $state): ?string => $state['title'] ?? null)
                    ->schema([
                        $this->text('eyebrow'),
                        $this->text('title', required: true),
                        Textarea::make('summary')->label($this->label('summary'))->maxLength(1000),
                        $this->text('metric', max: 40),
                        $this->text('metricLabel', max: 80),
                        $this->variant(['standard', 'spotlight'], null),
                    ]),
            ],
            WidgetComponentEnum::TranslucentStatBand => [
                $this->text('anchorId', max: 80, anchor: true),
                ...$this->intro(),
                $this->variant(['band', 'panel'], 'band'),
                Repeater::make('stats')->label($this->label('stats'))->defaultItems(0)->maxItems(6)->collapsible()
                    ->itemLabel(static fn (array $state): ?string => $state['label'] ?? null)
                    ->schema([
                        TextInput::make('value')->label($this->label('value'))->numeric()->required(),
                        $this->text('label', required: true),
                        $this->text('prefix', max: 12),
                        $this->text('suffix', max: 12),
                        TextInput::make('decimals')->label($this->label('decimals'))->numeric()->integer()->minValue(0)->maxValue(4)->default(0),
                    ]),
            ],
            WidgetComponentEnum::LayeredDepthHero => [
                $this->text('anchorId', max: 80, anchor: true),
                ...$this->intro(),
                $this->variant(['layered', 'flat-fallback'], 'layered'),
                Repeater::make('panes')->label($this->label('panes'))->defaultItems(0)->maxItems(5)->collapsible()
                    ->itemLabel(static fn (array $state): ?string => $state['label'] ?? null)
                    ->schema([
                        $this->text('label', required: true),
                    ]),
                TextInput::make('frontImage')->label($this->label('frontImage'))->maxLength(2000)
                    ->helperText(__('capell-theme-liquid-glass::blocks.fields.image_help')),
                $this->text('frontImageAlt', max: 200),
            ],
            WidgetComponentEnum::RefractionGrid => [
                ...$this->intro(),
                $this->variant(['even', 'featured'], 'even'),
                Repeater::make('tiles')->label($this->label('tiles'))->defaultItems(0)->maxItems(12)->collapsible()
                    ->itemLabel(static fn (array $state): ?string => $state['title'] ?? null)
                    ->schema([
                        $this->text('title', required: true),
                        Textarea::make('summary')->label($this->label('summary'))->maxLength(1000),
                        ...$this->image(),
                    ]),
            ],
            WidgetComponentEnum::FloatingGlassNav => [
                $this->text('label', max: 120),
                $this->variant(['pill', 'bar'], 'pill'),
                Repeater::make('items')->label($this->label('items'))->defaultItems(0)->maxItems(10)->collapsible()
                    ->itemLabel(static fn (array $state): ?string => $state['label'] ?? null)
                    ->schema([
                        $this->text('label', required: true),
                        TextInput::make('url')->label($this->label('url'))->required()->maxLength(2000)
                            ->helperText(__('capell-theme-liquid-glass::blocks.fields.url_help')),
                    ]),
            ],
        };
    }

    /**
     * @return array<int, Component>
     */
    private function intro(bool $eyebrow = true): array
    {
        return [
            ...$eyebrow ? [$this->text('eyebrow')] : [],
            $this->text('heading', max: 200),
            Textarea::make('summary')->label($this->label('summary'))->maxLength(1000),
        ];
    }

    /**
     * @return array<int, Component>
     */
    private function image(): array
    {
        return [
            TextInput::make('image')->label($this->label('image'))->maxLength(2000)
                ->helperText(__('capell-theme-liquid-glass::blocks.fields.image_help')),
            $this->text('imageAlt', max: 200),
        ];
    }

    /**
     * @param  list<string>  $values
     */
    private function variant(array $values, ?string $default): Select
    {
        return Select::make('variant')->label($this->label('variant'))
            ->options(array_combine($values, array_map(
                static fn (string $value): string => __('capell-theme-liquid-glass::blocks.variants.' . $value),
                $values,
            )))
            ->default($default)->required($default !== null)->placeholder(__('capell-theme-liquid-glass::blocks.fields.variant_inherit'));
    }

    private function text(string $name, int $max = 200, bool $required = false, bool $anchor = false): TextInput
    {
        $field = TextInput::make($name)->label($this->label($name))->maxLength($max)->required($required);

        return $anchor
            ? $field->regex('~\A[A-Za-z][A-Za-z0-9_-]*\z~')->helperText(__('capell-theme-liquid-glass::blocks.fields.anchorId_help'))
            : $field;
    }

    private function label(string $field): string
    {
        return __('capell-theme-liquid-glass::blocks.fields.' . $field);
    }
}
