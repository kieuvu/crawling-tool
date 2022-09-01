<?php

namespace App\Configs\Site\Extends;

use App\Configs\Site\SiteAbstract;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class StackOverFlow extends SiteAbstract
{
    public function rootUrl(): string
    {
        return "https://stackoverflow.com";
    }

    public function startPoints(): array
    {
        return [
            "https://stackoverflow.com/questions/32798552/how-to-prevent-model-events-from-firing-using-phpunit?noredirect=1&lq=1",
        ];
    }

    public function isValidUrl(string $url): bool
    {
        return
            preg_match("/^https:\/\/stackoverflow\.com/", $url) &&
            !preg_match("/^https:\/\/stackoverflow\.com\/users\//", $url);
    }

    public function canBeStored(string $url): bool
    {
        return preg_match("/^https:\/\/stackoverflow\.com\/questions\/[0-9]+\//", $url);
    }

    public function getData(DomCrawler $crawler): array
    {
        $data = [];

        $data['title'] = $crawler->filter('#question-header > .fs-headline1 > a')->text();

        return $data;
    }
}
