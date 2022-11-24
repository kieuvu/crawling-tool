<?php

namespace App\Configs\Site\Extends;

use App\Configs\Site\SiteAbstract;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class RocketLeague extends SiteAbstract
{
    public function rootUrl(): string
    {
        return "https://rocketleague.tracker.network";
    }

    public function startPoints(): array
    {
        $listStartPoints = [];
        foreach ($this->listStartPoints as $point) {
            $listStartPoints[] = "https://rocketleague.tracker.network/rocket-league/profile/epic/${point}/overview";
        };
        return $listStartPoints;
    }

    public function canBeStored(string $url): bool
    {
        return preg_match("/https:\/\/rocketleague\.tracker\.network\/rocket-league\/profile\/epic\/[0-9A-Za-z%&\?_\.-]+\/overview$/", $url);
    }

    public function getData(DomCrawler $crawler): array
    {
        $data = [];

        $data['game'] = "rocketleague";

        $data['title'] = $crawler->filter('#app > div.trn-wrapper > div.trn-container > div > main > div.content.no-card-margin > div.ph > div.ph__container > div.ph-details > div.ph-details__identifier > span > span > span')->count() > 0
            ? $crawler->filter('#app > div.trn-wrapper > div.trn-container > div > main > div.content.no-card-margin > div.ph > div.ph__container > div.ph-details > div.ph-details__identifier > span > span > span')->text()
            : null;

        $data['playlist'] = [
            [
                "Un-Ranked" => [
                    "Rating" => $crawler->filter('#app > div.trn-wrapper > div.trn-container > div > main > div.content.no-card-margin > div.site-container.trn-grid.trn-grid--vertical.trn-grid--small > div.trn-grid__sidebar-left > div > div > div:nth-child(1) > div.trn-table__container > table > tbody > tr:nth-child(1) > td.rating')->text(),
                ],
                "Ranked Duel 1v1" => [
                    "Rating" => $crawler->filter('#app > div.trn-wrapper > div.trn-container > div > main > div.content.no-card-margin > div.site-container.trn-grid.trn-grid--vertical.trn-grid--small > div.trn-grid__sidebar-left > div > div > div:nth-child(1) > div.trn-table__container > table > tbody > tr:nth-child(2) > td.rating')->text(),
                ],
                "Ranked Doubles 2v2" => [
                    "Rating" => $crawler->filter('#app > div.trn-wrapper > div.trn-container > div > main > div.content.no-card-margin > div.site-container.trn-grid.trn-grid--vertical.trn-grid--small > div.trn-grid__sidebar-left > div > div > div:nth-child(1) > div.trn-table__container > table > tbody > tr:nth-child(3) > td.rating')->text(),
                ],
                "Ranked Standard 3v3" => [
                    "Rating" => $crawler->filter('#app > div.trn-wrapper > div.trn-container > div > main > div.content.no-card-margin > div.site-container.trn-grid.trn-grid--vertical.trn-grid--small > div.trn-grid__sidebar-left > div > div > div:nth-child(1) > div.trn-table__container > table > tbody > tr:nth-child(4) > td.rating')->text(),
                ],
            ]
        ];
        return $data;
    }

    public function otherConfigOption()
    {
        return [
            "waitForFunction" => [
                "function" => "document.querySelector('#app > div.trn-wrapper > div.trn-container > div > main > div.content.no-card-margin > div.ph > div.ph__container > div.ph-details > div.ph-details__identifier > span > span > span') != null",
                "polling" => 100,
                "timeout" => 50000
            ],
            "timeout" => 960000,
            "randomUserAgent" => true,
        ];
    }
}
