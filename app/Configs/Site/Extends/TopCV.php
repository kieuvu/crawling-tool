<?php

namespace App\Configs\Site\Extends;

use App\Configs\Site\SiteAbstract;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class TopCV extends SiteAbstract
{
    public function rootUrl(): string
    {
        return 'https://www.topcv.vn';
    }

    public function startPoints(): array
    {
        return ['https://www.topcv.vn/viec-lam'];
    }

    public function isValidUrl(string $url): bool
    {
        return preg_match("/^https:\/\/www\.topcv\.vn/", $url) &&
            !preg_match("/^https:\/\/www\.topcv\.vn\/tim\-viec\-lam/", $url);
    }

    public function canBeStored(string $url): bool
    {
        return
            preg_match("/^https:\/\/www\.topcv\.vn\/viec\-lam\//", $url) ||
            preg_match("/^https:\/\/www\.topcv\.vn\/brand\/[a-zA-Z0-0\-]+\/tuyen\-dung\//", $url);
    }

    public function getData(DomCrawler $crawler): array
    {
        $data = [];

        $data['title'] = $crawler->filter('h1.job-title > a')->text();

        return $data;
    }
}
