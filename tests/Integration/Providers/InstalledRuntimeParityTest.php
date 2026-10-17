<?php

declare(strict_types=1);

use Capell\Core\Actions\EnablePackageAction;
use Capell\Core\Actions\RuntimeRefresh\RefreshInstalledPackageRuntimeAction;
use Capell\Core\Facades\CapellCore;
use Capell\Core\ThemeStudio\Theme\ThemeRegistry;
use Capell\Tests\Support\InstalledRuntimeParity;
use Capell\Tests\Support\InstalledRuntimeState;
use Capell\Tests\Support\InstalledRuntimeTestCase;

it('matches fresh installed boot after in-process installation and repeated refresh', function (): void {
    InstalledRuntimeParity::assertPackage('theme-liquid-glass');
});

it('activates a preloaded theme when enabling without legacy callback replay', function (): void {
    InstalledRuntimeState::run(static function (): void {
        $app = InstalledRuntimeTestCase::boot(InstalledRuntimeParity::manifest('theme-liquid-glass'), false, sourceRoot: dirname(__DIR__, 3));
        $registry = $app->make(ThemeRegistry::class);
        expect($registry->has('liquid-glass'))->toBeFalse();

        $package = CapellCore::getPackage('capell-app/theme-liquid-glass');
        EnablePackageAction::run($package);
        expect($registry->has('liquid-glass'))->toBeTrue();
        $definition = $registry->definition('liquid-glass');
        RefreshInstalledPackageRuntimeAction::run($package, replayBootedCallbacks: false);
        RefreshInstalledPackageRuntimeAction::run($package, replayBootedCallbacks: false);

        expect($registry->definition('liquid-glass'))->toBe($definition);
    });
});
