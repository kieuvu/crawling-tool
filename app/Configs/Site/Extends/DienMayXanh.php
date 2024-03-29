<?php

namespace App\Configs\Site\Extends;

use App\Configs\Site\SiteAbstract;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class DienMayXanh extends SiteAbstract
{
    public function rootUrl(): string
    {
        return 'https://www.dienmayxanh.com';
    }

    public function startPoints(): array
    {
        return ["https://www.dienmayxanh.com/khuyen-mai"];
    }

    public function isValidUrl(string $url): bool
    {
        return preg_match("/^https:\/\/www\.dienmayxanh\.com\/khuyen\-mai/", $url)
            || preg_match("/^https:\/\/www\.dienmayxanh\.com\/khuyen\-mai\/\?p=[0-9]+/", $url);
    }

    public function canBeStored(string $url): bool
    {
        return preg_match("/^https:\/\/www\.dienmayxanh\.com\/khuyen\-mai\/[a-zA-Z0-9]+/", $url);
    }

    public function formatUrl(string $url): string
    {
        $url = withoutUrlQueries($url, ['view']);

        if (preg_match("/^\?p=[0-9]+/", $url)) {
            return  "https://www.dienmayxanh.com/khuyen-mai/" . $url;
        }
        if (preg_match("/^\/[0-9A-Za-z&\?_\.-]+/", $url)) {
            return  "https://www.dienmayxanh.com" . $url;
        }

        return $url;
    }

    public function getData(DomCrawler $crawler): array
    {
        $data = [];

        $data['title'] = $crawler->filter('.titlebigKMDMX > h1')->count() > 0
            ? $crawler->filter('.titlebigKMDMX > h1')->text()
            : null;
        $data['content'] = $crawler->filter('.boldh2ofcontentnews')->count() > 0
            ? $crawler->filter('.boldh2ofcontentnews')->text()
            : null;

        return $data;
    }
}
