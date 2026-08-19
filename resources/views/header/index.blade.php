@php
    use Capell\Frontend\Facades\Frontend;

    $site = Frontend::site();
    $layout = Frontend::layout();
    $siteTitle = $site?->translation?->title ?? $site?->name;
@endphp

{{--
    Liquid Glass's own header chrome, wired through `Theme::meta.header_file`
    (see `layout/index.blade.php`'s `<x-dynamic-component>` fallback and
    `LiquidGlassThemeServiceProvider::registerLayoutAreas()`'s docblock for
    why this is the real extension point rather than a Blade view-chain
    override of `capell::header.index`, which is a class-aliased component
    and cannot be overridden by view path alone).

    Renders the shared `header` layout-builder area so any widgets an admin
    places there (e.g. a navigation widget) appear here, plus the theme's own
    brand mark, in the same translucent glass idiom as the rest of the theme.
--}}
<header
    class="liquid-glass-shell liquid-glass-chrome sticky top-0 z-30 border-b border-(--theme-foreground)/10"
>
    <div
        class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-5 sm:px-6 lg:px-8"
    >
        <a
            href="{{ $site?->siteDomain?->url ?? '/' }}"
            aria-label="{{ __('capell-theme-liquid-glass::generic.home') }}"
            class="liquid-glass-brand flex min-w-0 items-center gap-3 text-lg font-black text-(--theme-foreground)"
        >
            @if ($site?->logo || $site?->logoInverted)
                @if ($site?->logoInverted)
                    <x-capell::logo
                        :media="$site->logoInverted"
                        :class="'h-9 max-h-9 w-auto' . ($site?->logo ? ' hidden dark:block' : '')"
                    />
                @endif

                @if ($site?->logo)
                    <x-capell::logo
                        :media="$site->logo"
                        :class="'h-9 max-h-9 w-auto' . ($site?->logoInverted ? ' dark:hidden' : '')"
                    />
                @endif
            @else
                <span
                    class="liquid-glass-brand__mark"
                    aria-hidden="true"
                ></span>
                <span class="truncate">{{ $siteTitle }}</span>
            @endif
        </a>

        <x-capell::layout.area
            area="header"
            :layout="$layout"
        />
    </div>
</header>

<span
    id="main-content"
    tabindex="-1"
></span>
