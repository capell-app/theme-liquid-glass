# Theme Liquid Glass

<!-- prettier-ignore-start -->

## What It Does

Theme Liquid Glass adds translucent layers, depth, refraction grids, and floating navigation to Capell page presentation. Capell and Layout Builder retain ownership of the page data.

- **Price and availability:** Check current availability and price on the [Capell Marketplace listing](https://capell.app/extensions/theme-liquid-glass).
- **Licence:** Published under MIT in `composer.json`.
- **Requires:** `PHP ^8.4`, `capell-app/core ^1.0.72`, `capell-app/theme-foundation ^1.0`, `capell-app/frontend ^1.0`, `capell-app/layout-builder ^1.0`.
- **Install:** `composer require capell-app/theme-liquid-glass`.

Use Preview Theme Liquid Glass on existing content before Apply Theme Liquid Glass, which changes the selected site's public design; reapply its previous theme to restore that selection. Shared Theme Studio settings saves can affect public pages. For fictional demo content, name a disposable target with --sites and review --url and --languages; unscoped setup refuses existing sites, scoped repeats preserve site configuration unless --force deliberately replaces its theme, enabled state, requested site translations and domains, while demo pages, widgets and demo layouts are rewritten on scoped repeats.

## Who It Is For

**For developers:** Typed widget keys and a layout-native provider connect bespoke presentation to the shared Layout Builder container pipeline.

**For teams:** Design teams can apply a consistent glass treatment across hero, grid, and navigation sections without relocating content into theme-specific storage.

## Quick Start

1. Install the package: `composer require capell-app/theme-liquid-glass`.
2. See it working on a disposable site: run `php artisan capell:theme-liquid-glass-demo --sites="Theme demo"`. Choose a new site name for your demo; keep customer sites for the Preview → Apply workflow above.
3. Run `php artisan list` and verify the package command signatures are registered.

## Configure

- **Commands:** `php artisan capell:theme-liquid-glass-demo`.

## Limits And Pitfalls

- Install the required Capell packages at the versions in `composer.json`: `capell-app/core ^1.0.72`, `capell-app/theme-foundation ^1.0`, `capell-app/frontend ^1.0`, `capell-app/layout-builder ^1.0`.
- Keep public Blade and cached HTML free of authoring markers, model IDs, permissions, signed editor URLs, and lazy database queries.
- Custom write integrations must preserve invalidation for `theme-liquid-glass` cache tags.

## Screens And Workflow

![Liquid Glass Homepage](docs/screenshots/liquid-glass-homepage.webp)

![Liquid Glass Directory](docs/screenshots/liquid-glass-directory.webp)

- Liquid Glass Homepage (frontend).
- Liquid Glass Directory (frontend).
- Liquid Glass Detail Article (frontend).
- Liquid Glass Contact (frontend).
- Liquid Glass Empty State (frontend).
- Liquid Glass Page Not Found (frontend).
- Liquid Glass Call To Action (frontend).

## Install Impact

- Required packages: `capell-app/core`, `capell-app/theme-foundation`, `capell-app/frontend`, `capell-app/layout-builder`.
- Admin navigation: declares `admin-page: ThemeManagementPageContribution`; each Filament page or resource controls its own navigation visibility.
- Admin/editor extensions: none declared.
- Permissions: no package permission declarations or Shield gates detected; host access rules still apply.
- Public routes: none declared.
- Database changes: no package migrations declared.
- Config: no package config files.
- Settings: no package settings declared.
- Queues or schedules: none declared.
- Cache tags: `theme-liquid-glass`.
- Commands: `capell:theme-liquid-glass-demo`.

## Troubleshooting

| Symptom | Likely cause | Check | Fix |
| --- | --- | --- | --- |
| Package surface is missing after install | Provider or manifest is not loaded | Confirm `capell.json`, package `composer.json`, and provider registration | Reinstall the package, refresh Composer autoload, and clear host caches |
| Public output leaks unexpected state | Render data, cache variation, or authoring boundary has regressed | Check public Blade, cache tags, and public-output safety tests | Move data loading out of Blade and rerun the package public-output tests |

## Upgrading

No manual steps between minor versions.

Read [CHANGELOG.md](CHANGELOG.md) before upgrading.

## Next Steps

- [Package docs](docs/README.md)
- [Overview](docs/overview.md)
- [Troubleshooting](#troubleshooting)
- [Developer reference](docs/reference.md)
- [Marketplace assets](docs/assets/marketplace/)
- [Capell content language plan](../../docs/CONTENT_LANGUAGE_PLAN.md)
- [Capell documentation design system](../../docs/DESIGN_SYSTEM.md)
- [Capell and package ERD notes](../../docs/erd/capell-and-package-erds.md)
- Related packages: [Foundation Theme](../theme-foundation/README.md), [Layout Builder](../layout-builder/README.md).

<!-- prettier-ignore-end -->
