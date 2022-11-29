<?php

namespace App\Configs\Site;

use Illuminate\Filesystem\Filesystem as File;

class SiteMapping
{
    public static array $alias = [
        "FortniteTrackergg" => "fortnite",
    ];

    public static function getAllSites()
    {
        $sites = [];
        $path = app_path();
        $sitePath = $path . "/Configs/Site/Extends/";
        $siteConfigFiles = (new File())->allFiles($sitePath);

        foreach ($siteConfigFiles as $file) {
            $fileName = str_replace(".php", "", $file->getFilename());
            $namespace = 'App\\Configs\\Site\\Extends' . '\\' . $fileName;

            if (array_key_exists($fileName, self::$alias)) {
                $sites[self::$alias[$fileName]] = $namespace;
            } else {
                $sites[$fileName] = $namespace;
            }
        }

        return $sites;
    }

    public static function getSiteConfig($site): SiteInterface
    {
        return app(self::getAllSites()[$site]);
    }
}
