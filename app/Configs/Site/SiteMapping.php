<?php

namespace App\Configs\Site;

use App\Configs\Site\Extends\DienMayXanh;

class SiteMapping
{
    public static array $sites = [
        'dmx' => DienMayXanh::class,
    ];

    public static function getSiteConfig($site): SiteInterface
    {
        return app(self::$sites[$site]);
    }
}
