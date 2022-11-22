<?php

namespace App\Configs\Site;

use App\Libs\Browser\BrowserInterface;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

interface SiteInterface
{
    public function rootUrl(): string;
    public function startPoints(): array;
    public function isValidUrl(string $url): bool;
    public function canBeStored(string $url): bool;
    public function formatUrl(string $url): string;
    public function getData(DomCrawler $crawler): array;
    public function getSpecialData(BrowserInterface $browser, DomCrawler $domCrawler, $url, $site);
}
