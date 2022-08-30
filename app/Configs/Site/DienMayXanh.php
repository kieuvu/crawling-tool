<?php

namespace App\Configs\Site;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class DienMayXanh extends SiteAbstract
{
    public function __construct()
    {
    }

    public function rootUrl(): string
    {
        return 'https://www.dienmayxanh.com';
    }

    public function startUrls(): array
    {
        return ["https://www.dienmayxanh.com/khuyen-mai"];
    }

    public function isValidUrl(string $url): bool
    {
        return preg_match("/https:\/\/www\.dienmayxanh\.com\/khuyen\-mai/", $url);
    }

    public function canBeStored(string $url): bool
    {
        return preg_match("/https:\/\/www\.dienmayxanh\.com\/khuyen\-mai\/[a-zA-Z0-9]+/", $url);
    }

    public function getData(DomCrawler $crawler, string $url)
    {
        $data = [];

        $data['title'] = $crawler->filter('.titlebigKMDMX > h1')->text();

        return $data;
    }
}
