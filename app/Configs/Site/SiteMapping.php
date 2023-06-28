<?php

namespace App\Configs\Site;

use Illuminate\Filesystem\Filesystem as File;
use App\Exceptions\ConfigFileNotFoundException;

class SiteMapping
{
    public static array $alias = [
        "fortnite" => "FortniteTrackergg",
    ];

    public static function getAllConfigFiles(): array
    {
        $sites           = [];
        $path            = app_path();
        $sitePath        = $path . "/Configs/Site/Extends/";
        $siteConfigFiles = (new File())->allFiles($sitePath);

        foreach ($siteConfigFiles as $file) {
            $fileName  = str_replace(".php", "", $file->getFilename());
            $namespace = 'App\\Configs\\Site\\Extends' . '\\' . $fileName;

            $sites[$fileName] = $namespace;
        }

        return $sites;
    }

    public static function show(): void
    {
        $alias = array_flip(self::$alias);
        foreach (SiteMapping::getAllConfigFiles() as $key => $value) {
            p_info($key, $value) || (array_key_exists($key, $alias)) && p_info($alias[$key], $value);
        }
    }

    public static function getSiteConfig($site): SiteInterface
    {
        try {
            return app(self::getAllConfigFiles()[self::$alias[$site] ?? $site]);
        } catch (\Exception $ex) {
            logger()->error($ex->getMessage());
            throw new ConfigFileNotFoundException("Can't not find '$site'");
        }
    }
}
