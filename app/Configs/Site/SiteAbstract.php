<?php

namespace App\Configs\Site;

abstract class SiteAbstract implements SiteInterface
{
    public function __construct()
    {
    }

    public function isValidUrl(string $url): bool
    {
        return true;
    }

    public function canBeStored(string $url): bool
    {
        return true;
    }

    public function formatUrl(string $url): string
    {
        return $url;
    }
}
