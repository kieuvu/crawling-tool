<?php

namespace App\Configs\Site;

use Exception;
use Illuminate\Filesystem\Filesystem as File;

class SiteMapping
{
    public static array $alias = [
        "fortnite" => "FortniteTrackergg",
    ];

    public static function getAllConfigFiles(): array
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

    public static function show(): void
    {
        $alias = array_flip(self::$alias);
        foreach (SiteMapping::getAllConfigFiles() as $key => $value) {
            pinfo($key, $value) || (array_key_exists($key, $alias)) && pinfo($alias[$key], $value);
        }
    }

    public static function getSiteConfig($site): SiteInterface
    {
        try {
            return (array_key_exists($site, self::$alias))
                ? app(self::getAllConfigFiles()[self::$alias[$site]])
                : app(self::getAllConfigFiles()[$site]);
        } catch (\Exception $ex) {
            throw new Exception("Can't not find '$site'");
        }
    }
}
