<?php

declare(strict_types=1);

use Capell\Core\Enums\VendorAssetEnum;
use Capell\Core\Facades\CapellCore;
use Capell\Core\Models\Layout;
use Capell\Core\Models\Page;
use Capell\Core\ThemeStudio\Data\ThemeFrontendBuildAssetsData;
use Capell\Core\ThemeStudio\Theme\ThemeRegistry;
use Capell\FoundationTheme\Data\ThemeDemoInstallData;
use Capell\FoundationTheme\Support\Demo\ThemeDemoPageInstaller;
use Capell\LayoutBuilder\Models\Widget;
use Capell\ThemeLiquidGlass\Health\ThemeLiquidGlassHealthCheck;
use Capell\ThemeLiquidGlass\LiquidGlassThemeServiceProvider;
use Capell\ThemeLiquidGlass\Support\Demo\LiquidGlassDemoContent;

it('defines the Liquid Glass free renderer contract', function (): void {
    $definition = LiquidGlassThemeServiceProvider::definition();

    expect($definition->package)->toBe('capell-app/theme-liquid-glass')
        ->and($definition->key)->toBe(LiquidGlassThemeServiceProvider::THEME_KEY)
        ->and($definition->assets)->toBe([])
        ->and($definition->frontendBuildAssets())->toBeInstanceOf(ThemeFrontendBuildAssetsData::class)
        ->and($definition->frontendBuildAssets()?->cssSource)->toBe('resources/css/theme-liquid-glass.css')
        ->and($definition->frontendBuildAssets()?->cssBuildInput)->toBe('resources/css/capell/themes/liquid-glass.css')
        ->and($definition->frontendBuildAssets()?->condition)->toBe('theme-css:liquid-glass')
        ->and($definition->presets)->toHaveCount(3)
        ->and($definition->presetOptions())->toBe([
            'clarity' => 'Clarity',
            'prism' => 'Prism',
            'graphite' => 'Graphite',
        ])
        ->and($definition->runtime->value)->toBe('blade')
        ->and($definition->extends)->toBe('default')
        ->and($definition->tags)->toContain('Glass')
        ->and(data_get($definition->frontend, 'editor.groups.identity'))->toBe(['glassDepth'])
        ->and(data_get($definition->frontend, 'editor.tokens.glassDepth.options'))->toBe(['restrained', 'balanced', 'prismatic'])
        ->and($definition->presets[0]->values['glassDepth'])->toBe('balanced')
        ->and($definition->presets[1]->values['glassDepth'])->toBe('prismatic')
        ->and($definition->presets[2]->values['glassDepth'])->toBe('restrained')
        ->and(ThemeLiquidGlassHealthCheck::compatibleCapellApiVersion())->toBe('^1.0');
});

it('captures commercial screenshot proof from the real seeded routes', function (): void {
    $manifest = capell_json_file_array(dirname(__DIR__, 2) . '/docs/screenshots.json');
    $entries = $manifest['entries'] ?? [];
    throw_unless(is_array($entries), RuntimeException::class, 'Expected screenshot entries to be an array.');

    expect($entries)->toHaveCount(21);

    foreach ($entries as $entry) {
        throw_unless(is_array($entry), RuntimeException::class, 'Expected each screenshot entry to be an array.');

        expect($entry)->toBeArray()
            ->and($entry['url'] ?? null)->toBe($entry['target'] ?? null)
            ->and($entry['url'] ?? null)->not->toStartWith('/screenshot-fixtures/');

        $entryId = $entry['id'] ?? null;

        if (is_string($entryId) && str_starts_with($entryId, 'liquid-glass-homepage')) {
            expect($entry['interactions'] ?? null)->toBe([
                ['type' => 'scrollIntoView', 'selector' => '.layered-depth-hero-pane:last-child'],
                ['type' => 'scrollIntoView', 'selector' => '.refraction-grid'],
                ['type' => 'scrollIntoView', 'selector' => '.liquid-glass-footer__home'],
            ]);
        }
    }
});

it('registers Liquid Glass as definition-only with no legacy renderer', function (): void {
    CapellCore::clearPackages();
    CapellCore::forcePackageInstalled(LiquidGlassThemeServiceProvider::$packageName);

    $registry = new ThemeRegistry;
    $provider = new LiquidGlassThemeServiceProvider(app());
    $provider->boot($registry);

    expect($registry->has(LiquidGlassThemeServiceProvider::THEME_KEY))->toBeTrue();

    CapellCore::clearPackages();
});

it('registers Liquid Glass theme assets when the package is installed', function (): void {
    CapellCore::clearPackages();
    CapellCore::forcePackageInstalled(LiquidGlassThemeServiceProvider::$packageName);

    $registry = new ThemeRegistry;
    $provider = new LiquidGlassThemeServiceProvider($this->app);
    $provider->boot($registry);

    $packageImports = CapellCore::getVendorAssetsForType(VendorAssetEnum::TailwindImport)
        ->where('packageName', LiquidGlassThemeServiceProvider::$packageName)
        ->pluck('value')
        ->all();

    $packageSources = CapellCore::getVendorAssetsForType(VendorAssetEnum::TailwindSource)
        ->where('packageName', LiquidGlassThemeServiceProvider::$packageName)
        ->pluck('value')
        ->all();

    expect($packageImports)->toContain('resources/css/theme-liquid-glass.css')
        ->and($packageSources)->toContain('resources/views/**/*.blade.php');

    CapellCore::clearPackages();
});

it('seeds real Layout containers and a page-content Widget through ThemeDemoPageInstaller', function (): void {
    ThemeDemoPageInstaller::run(
        data: new ThemeDemoInstallData(
            siteNames: ['Liquid Glass Definition Test'],
            languageCodes: ['en'],
            baseUrl: 'https://liquid-glass.definition-test.test',
        ),
        themeKey: LiquidGlassThemeServiceProvider::THEME_KEY,
        themeName: 'Liquid Glass',
        contentProvider: new LiquidGlassDemoContent,
    );

    $homepage = Page::query()
        ->where('meta->theme_demo->theme_key', LiquidGlassThemeServiceProvider::THEME_KEY)
        ->where('meta->theme_demo->surface', 'homepage')
        ->firstOrFail();

    expect($homepage)->toBeInstanceOf(Page::class);

    $layout = $homepage->layout;

    throw_unless($layout instanceof Layout, RuntimeException::class, 'Expected homepage layout.');

    expect($layout)->toBeInstanceOf(Layout::class)
        ->and($layout->containers)->toBe([
            'main' => [
                'widgets' => [
                    ['widget_key' => 'page-content', 'occurrence' => 1],
                    ['widget_key' => 'liquid-glass-floating-glass-nav-homepage-1', 'occurrence' => 1],
                    ['widget_key' => 'liquid-glass-layered-depth-hero-homepage-1', 'occurrence' => 1],
                    ['widget_key' => 'liquid-glass-glass-feature-card-homepage-1', 'occurrence' => 1],
                    ['widget_key' => 'liquid-glass-translucent-stat-band-homepage-1', 'occurrence' => 1],
                    ['widget_key' => 'liquid-glass-refraction-grid-homepage-1', 'occurrence' => 1],
                    ['widget_key' => 'liquid-glass-showcase-homepage-1', 'occurrence' => 1],
                    ['widget_key' => 'liquid-glass-presets-homepage-1', 'occurrence' => 1],
                    ['widget_key' => 'liquid-glass-cta-homepage-1', 'occurrence' => 1],
                ],
            ],
        ]);

    $widget = Widget::query()->where('key', 'page-content')->firstOrFail();

    expect($widget)->toBeInstanceOf(Widget::class);

    $ctaWidget = Widget::query()->where('key', 'liquid-glass-cta-homepage-1')->firstOrFail();

    expect($ctaWidget)->toBeInstanceOf(Widget::class)
        ->and($ctaWidget->component)->toBe('capell.widget.liquid-glass.cta')
        ->and($ctaWidget->meta['heading'] ?? null)->toBe('Bring your pages onto the glass');

    $showcaseWidget = Widget::query()->where('key', 'liquid-glass-showcase-homepage-1')->firstOrFail();

    expect($showcaseWidget)->toBeInstanceOf(Widget::class)
        ->and($showcaseWidget->component)->toBe('capell.widget.liquid-glass.showcase')
        ->and($showcaseWidget->meta['heading'] ?? null)->toBe('Teams that ship on the glass');

    $presetsWidget = Widget::query()->where('key', 'liquid-glass-presets-homepage-1')->firstOrFail();

    expect($presetsWidget)->toBeInstanceOf(Widget::class)
        ->and($presetsWidget->component)->toBe('capell.widget.liquid-glass.presets')
        ->and($presetsWidget->meta['heading'] ?? null)->toBe('One glass system, three token-driven presets');
});

it('preserves the real, previously-authored section copy per surface for later widget wiring', function (): void {
    $content = new LiquidGlassDemoContent;

    $homepageCopy = $content->sectionCopy('homepage');

    expect($homepageCopy)->toHaveCount(11)
        ->and(data_get($homepageCopy, '1.anchorId'))->toBe('depth')
        ->and(data_get($homepageCopy, '2.anchorId'))->toBe('features')
        ->and(data_get($homepageCopy, '3.anchorId'))->toBe('proof');

    $types = array_column($homepageCopy, 'type');

    expect($types)->toBe([
        'floating-glass-nav',
        'layered-depth-hero',
        'glass-feature-card',
        'translucent-stat-band',
        'refraction-grid',
        'features',
        'showcase',
        'presets',
        'proof',
        'content-listing',
        'cta',
    ]);

    $copyByType = collect($homepageCopy)->keyBy('type');

    $features = $copyByType->get('features');
    throw_unless(is_array($features), RuntimeException::class, 'Expected homepage feature copy.');
    $featureItems = is_array($features['features'] ?? null) ? $features['features'] : [];
    $firstFeature = is_array($featureItems[0] ?? null) ? $featureItems[0] : [];

    expect($features['heading'])->toBe('Surfaces that stay legible through the glass')
        ->and($firstFeature['title'])->toBe('Frosted panels with depth');

    $showcase = $copyByType->get('showcase');
    throw_unless(is_array($showcase), RuntimeException::class, 'Expected homepage showcase copy.');
    $showcaseItems = is_array($showcase['items'] ?? null) ? $showcase['items'] : [];
    $firstShowcaseItem = is_array($showcaseItems[0] ?? null) ? $showcaseItems[0] : [];

    expect($showcase['heading'])->toBe('Teams that ship on the glass')
        ->and($firstShowcaseItem['title'])->toBe('Marlow Studio launch');

    $presets = $copyByType->get('presets');
    throw_unless(is_array($presets), RuntimeException::class, 'Expected homepage preset copy.');
    $presetItems = is_array($presets['presets'] ?? null) ? $presets['presets'] : [];
    $firstPreset = is_array($presetItems[0] ?? null) ? $presetItems[0] : [];

    expect($presets['heading'])->toBe('One glass system, three token-driven presets')
        ->and($firstPreset['name'])->toBe('Clarity');

    $proof = $copyByType->get('proof');
    throw_unless(is_array($proof), RuntimeException::class, 'Expected homepage proof copy.');
    $proofItems = is_array($proof['items'] ?? null) ? $proof['items'] : [];
    $firstProofItem = is_array($proofItems[0] ?? null) ? $proofItems[0] : [];

    expect($firstProofItem['quote'])->toBe('The glass panels gave our launch page depth without losing legibility.');

    $ctaSurfaceCopy = $content->sectionCopy('cta');

    expect(array_column($ctaSurfaceCopy, 'type'))->toBe(['presets', 'proof', 'cta']);

    $notFoundCopy = $content->sectionCopy('not-found');

    expect(array_column($notFoundCopy, 'type'))->toBe(['cta'])
        ->and($notFoundCopy[0]['heading'])->toBe('Bring your pages onto the glass');

    expect($content->sectionCopy('unknown-surface'))->toBe([]);
});

it('preserves the real, previously-authored hero copy per surface for later widget wiring', function (): void {
    $content = new LiquidGlassDemoContent;

    $homepageHero = $content->heroCopy('homepage');

    expect($homepageHero['eyebrow'])->toBe('Liquid Glass')
        ->and($homepageHero['heading'])->toBe('A modern glass surface for launch and service pages')
        ->and($homepageHero['mediaAlt'])->toBe('Layered translucent glass panels');

    $directoryHero = $content->heroCopy('directory');

    expect($directoryHero['eyebrow'])->toBe('Listing')
        ->and($directoryHero['mediaAlt'])->toBe('Translucent content cards in a glass listing');

    $detailHero = $content->heroCopy('detail');

    expect($detailHero['eyebrow'])->toBe('Story')
        ->and($detailHero['mediaAlt'])->toBe('A launch page rebuilt on the glass section rhythm');

    $contactHero = $content->heroCopy('contact');
    $contactActions = $contactHero['actions'] ?? null;

    throw_unless(is_array($contactActions), RuntimeException::class, 'Expected contact hero actions.');

    expect($contactHero['eyebrow'])->toBe('Contact')
        ->and($contactActions[0] ?? null)->toBe(['label' => 'Get in touch', 'url' => 'mailto:studio@liquidglass.example', 'style' => 'primary'])
        ->and($contactHero['mediaAlt'])->toBe('A polished glass lead journey');

    $emptyHero = $content->heroCopy('empty');

    expect($emptyHero['eyebrow'])->toBe('Listing')
        ->and($emptyHero['heading'])->toBe('No content cards match that filter yet')
        ->and($emptyHero['summary'])->toBe('Nothing matches the current filter. Clear it to see every card on the glass, or jump straight to the features.');

    $notFoundHero = $content->heroCopy('not-found');
    $notFoundActions = $notFoundHero['actions'] ?? null;

    throw_unless(is_array($notFoundActions), RuntimeException::class, 'Expected not-found hero actions.');

    expect($notFoundHero['eyebrow'])->toBe('404')
        ->and($notFoundHero['heading'])->toBe('This page slipped through the glass')
        ->and($notFoundHero['summary'])->toBe('The link is broken or the page has moved. Head back to the features, or start a conversation.')
        ->and($notFoundActions[0] ?? null)->toBe(['label' => 'Back to home', 'url' => '/', 'style' => 'primary']);

    $ctaHero = $content->heroCopy('cta');

    expect($ctaHero['eyebrow'])->toBe('Get started')
        ->and($ctaHero['summary'])->toBe('Move launch, listing, and lead pages onto translucent panels with a section rhythm that stays crisp from first view to conversion.')
        ->and($ctaHero['mediaAlt'])->toBe('Translucent glass panels for launch pages');

    expect($content->heroCopy('unknown-surface'))->toBe([]);
});

it('preserves the empty surface\'s real, previously-authored inline empty-state listing copy for later widget wiring', function (): void {
    $content = new LiquidGlassDemoContent;

    $emptyStateListing = $content->emptyStateListingCopy();

    expect($emptyStateListing)->toBe([
        'type' => 'content-listing',
        'heading' => 'Nothing to show on the glass here',
        'summary' => 'When content lands in this group it appears here as translucent cards, newest first.',
        'variant' => 'editorial',
        'items' => [],
    ]);
});
