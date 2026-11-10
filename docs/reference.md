# Theme Liquid Glass reference

<!-- prettier-ignore-start -->

Developer reference for `capell-app/theme-liquid-glass`. Start with the [README](../README.md) for what the extension does and how to install it.

## Package Details

Theme Liquid Glass is an **Available**, **No schema impact** Capell theme in the **Capell Themes** product group. It ships as `capell-app/theme-liquid-glass` and extends these surfaces: frontend.

- Status: Available
- Tier: free
- Bundle: themes
- Composer package: `capell-app/theme-liquid-glass`
- Namespace: `Capell\ThemeLiquidGlass`
- Theme key: `liquid-glass`

## Technical Shape

### Service providers

- `Capell\ThemeLiquidGlass\LiquidGlassThemeServiceProvider`
- `Capell\ThemeLiquidGlass\Providers\ConsoleServiceProvider`

### Actions

- `InstallLiquidGlassThemeDemoAction`

### Command signatures

- `capell:theme-liquid-glass-demo`

### Console command classes

- `DemoCommand`

### Manifest contributions

- `admin-page: Capell\ThemeLiquidGlass\Manifest\ThemeManagementPageContribution`

### Health checks

- `Capell\ThemeLiquidGlass\Health\ThemeLiquidGlassHealthCheck`

### Blade views

- `packages/theme-liquid-glass/resources/views/footer.blade.php`
- `packages/theme-liquid-glass/resources/views/header/index.blade.php`
- `packages/theme-liquid-glass/resources/views/sections/content-listing.blade.php`
- `packages/theme-liquid-glass/resources/views/sections/cta.blade.php`
- `packages/theme-liquid-glass/resources/views/sections/features.blade.php`
- `packages/theme-liquid-glass/resources/views/sections/footer.blade.php`
- `packages/theme-liquid-glass/resources/views/sections/hero.blade.php`
- `packages/theme-liquid-glass/resources/views/sections/navigation.blade.php`
- `packages/theme-liquid-glass/resources/views/sections/presets.blade.php`
- `packages/theme-liquid-glass/resources/views/sections/proof.blade.php`
- `packages/theme-liquid-glass/resources/views/sections/showcase.blade.php`
- `packages/theme-liquid-glass/resources/views/widget/cta.blade.php`
- `packages/theme-liquid-glass/resources/views/widget/floating-glass-nav.blade.php`
- `packages/theme-liquid-glass/resources/views/widget/glass-feature-card.blade.php`
- `packages/theme-liquid-glass/resources/views/widget/layered-depth-hero.blade.php`
- `packages/theme-liquid-glass/resources/views/widget/presets.blade.php`
- `packages/theme-liquid-glass/resources/views/widget/refraction-grid.blade.php`
- `packages/theme-liquid-glass/resources/views/widget/showcase.blade.php`
- `packages/theme-liquid-glass/resources/views/widget/translucent-stat-band.blade.php`

### Cache tags

- `theme-liquid-glass`


## Data Model

This theme has no schema impact. It relies on core Capell site, page, locale, and theme records instead of declaring package-owned tables.

<!-- prettier-ignore-end -->
