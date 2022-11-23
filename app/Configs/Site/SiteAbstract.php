<?php

namespace App\Configs\Site;

use App\Libs\Browser\BrowserInterface;
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

    public function getSpecialData(BrowserInterface $browser, DomCrawler $domCrawler, $url, $site)
    {
        return false;
    }

    public function otherConfigOption()
    {
        return [];
    }
}
