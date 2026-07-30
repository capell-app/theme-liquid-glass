@php
    use Capell\Frontend\Facades\Frontend;

    $site = Frontend::site();
    $layout = Frontend::layout();
    $siteTitle = $site?->translation?->title ?? $site?->name;
@endphp

{{--
    Liquid Glass's own footer chrome, wired through `Theme::meta.footer_file`
    (see `layout/index.blade.php`'s `<x-dynamic-component>` fallback and
    `LiquidGlassThemeServiceProvider::registerLayoutAreas()`'s docblock).

    Renders the shared `footer` layout-builder area so any widgets an admin
    places there appear here, in the same translucent glass idiom as the
    rest of the theme.
--}}
<footer
    class="glass-surface border-t border-(--theme-foreground)/10 bg-(--theme-surface)/70 px-5 py-12 sm:px-6 lg:px-8"
>
    <h2 class="sr-only">
        {{ __('capell-theme-liquid-glass::generic.footer') }}
    </h2>

    <div class="mx-auto max-w-7xl">
        <div
            class="grid gap-5 sm:grid-cols-[minmax(0,1fr)_minmax(16rem,0.7fr)] sm:items-end"
        >
            <div>
                <p class="text-2xl font-black text-(--theme-foreground)">{{ $siteTitle }}</p>
                <p class="mt-2 max-w-xl text-sm leading-6 text-(--theme-foreground)/65">
                    {{ __('capell-theme-liquid-glass::generic.footer_tagline') }}
                </p>
            </div>

            <a
                href="{{ $site?->siteDomain?->url ?? '/' }}"
                class="liquid-glass-footer__home justify-self-start text-sm font-bold text-(--theme-foreground) sm:justify-self-end"
            >
                {{ __('capell-theme-liquid-glass::generic.back_home') }}
            </a>
        </div>

        <div class="mt-8">
            <x-capell::layout.area
                area="footer"
                :layout="$layout"
            />
        </div>

        <div
            class="mt-10 border-t border-(--theme-foreground)/10 pt-5 text-xs text-(--theme-foreground)/55"
        >
            {{ __('capell-theme-liquid-glass::generic.copyright', ['year' => now()->year, 'site' => $siteTitle]) }}
        </div>
    </div>
</footer>
