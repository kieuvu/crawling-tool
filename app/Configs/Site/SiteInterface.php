<?php

namespace App\Configs\Site;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

interface SiteInterface
{
    public function rootUrl(): string;
    public function startUrls(): array;
    public function isValidUrl(string $url): bool;
    public function canBeStored(string $url): bool;
    public function formatUrl(string $url): string;
    public function getData(DomCrawler $crawler);
}
