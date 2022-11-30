<?php

namespace App\Configs\Site;

use Exception;
use Illuminate\Filesystem\Filesystem as File;

class SiteMapping
{
    public static array $alias = [
        "fortnite" => "FortniteTrackergg",
    ];

    public static function getAllSites(): array
    {
        $sites = [];
        $path = app_path();
        $sitePath = $path . "/Configs/Site/Extends/";
        $siteConfigFiles = (new File())->allFiles($sitePath);

        foreach ($siteConfigFiles as $file) {
            $fileName = str_replace(".php", "", $file->getFilename());
            $namespace = 'App\\Configs\\Site\\Extends' . '\\' . $fileName;

            $sites[$fileName] = $namespace;
        }

        return $sites;
    }

    public static function getSiteConfig($site): SiteInterface
    {
        try {
            return (array_key_exists($site, self::$alias))
                ? app(self::getAllSites()[self::$alias[$site]])
                : app(self::getAllSites()[$site]);
        } catch (\Exception $ex) {
            throw new Exception("Can't not find '$site'");
        }
    }
}
