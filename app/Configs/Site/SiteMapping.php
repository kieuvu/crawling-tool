<?php

namespace App\Configs\Site;

use App\Configs\Site\Extends\DienMayXanh;
use App\Configs\Site\Extends\StackOverFlow;
use App\Configs\Site\Extends\TopCV;

class SiteMapping
{
    public static array $sites = [
        'dmx'   => DienMayXanh::class,
        'topcv' => TopCV::class,
        'sof'   => StackOverFlow::class,
    ];

    public static function getSiteConfig($site): SiteInterface
    {
        return app(self::$sites[$site]);
    }
}
