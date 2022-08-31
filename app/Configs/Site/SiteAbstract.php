<?php

namespace App\Configs\Site;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

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

    public function getData(DomCrawler $crawler): array
    {
        return [];
    }
}
