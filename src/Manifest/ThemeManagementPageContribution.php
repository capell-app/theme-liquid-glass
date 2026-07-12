<?php

declare(strict_types=1);

namespace Capell\ThemeLiquidGlass\Manifest;

use Capell\Core\Contracts\Extensions\ExtensionContribution;

final class ThemeManagementPageContribution implements ExtensionContribution
{
    public static function compatibleCapellApiVersion(): string
    {
        return '^0.0';
    }
}
