<?php

declare(strict_types=1);

namespace Capell\ThemeLiquidGlass\Actions;

use Capell\FoundationTheme\Contracts\InstallsThemeDemo;
use Capell\FoundationTheme\Data\ThemeDemoInstallData;
use Capell\FoundationTheme\Support\Demo\ThemeDemoPageInstaller;
use Capell\ThemeLiquidGlass\Support\Demo\LiquidGlassDemoContent;
use Lorisleiva\Actions\Concerns\AsObject;

final class InstallLiquidGlassThemeDemoAction implements InstallsThemeDemo
{
    use AsObject;

    public function handle(ThemeDemoInstallData $data): int
    {
        return ThemeDemoPageInstaller::run($data, 'liquid-glass', 'Liquid Glass', new LiquidGlassDemoContent);
    }
}
