<?php

namespace App\Configs\Site\Extends;

use App\Configs\Site\SiteAbstract;
use App\Libs\Browser\BrowserInterface;
use App\Services\UrlService;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class FortniteTrackergg extends SiteAbstract
{
    public function rootUrl(): string
    {
        return "https://fortnitetracker.com";
    }

    public function startPoints(): array
    {
        return [
            "https://fortnitetracker.com/leaderboards/all/Top1?mode=all&page=1"
        ];
    }

    public function formatUrl(string $url): string
    {
        $url = preg_replace('/\s+/', '%20', $url);
        if (preg_match("/^\/[0-9A-Za-z&\?_\.-]+/", $url)) {
            return extractUrlQueries($this->rootUrl() . $url);
        }
        return extractUrlQueries($url);
    }

    public function isValidUrl(string $url): bool
    {
        return
            preg_match("/^https:\/\/fortnitetracker\.com\/leaderboards\/all\/Top1\?mode\=all\&page\=[0-9]+/", $url) || $this->canBeStored($url);
    }

    public function canBeStored(string $url): bool
    {
        return preg_match("/^https:\/\/fortnitetracker\.com\/profile\/all\/[0-9A-Za-z%&\?_\.-]+$/", $url);
    }

    public function getData(DomCrawler $crawler): array
    {
        $data = [];

        $data['game'] = "fortnite";
        $data['player'] = $crawler->filter('h1.trn-profile-header__name > span')->count() > 0
            ? $crawler->filter('h1.trn-profile-header__name ')->text() : null;
        $data['title'] = "Player: " . $data['player'];

        $data["details"] = [
            "lifetime" => [
                "matches"
                => $crawler->filter('#profile > div.trn-scont.ftr-overview-grid > div > div.trn-card__header > div.trn-card__header-subline.trn--mobile-hidden')->count() > 0
                    ? $crawler->filter('#profile > div.trn-scont.ftr-overview-grid > div > div.trn-card__header > div.trn-card__header-subline.trn--mobile-hidden')->text()
                    : null,
                'wins'
                => $crawler->filter('#profile > div.trn-scont.ftr-overview-grid > div > div:nth-child(3) > div > div:nth-child(1) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('#profile > div.trn-scont.ftr-overview-grid > div > div:nth-child(3) > div > div:nth-child(1) > div.trn-defstat__values > div')->text()
                    : null,
                "win_percentage"
                => $crawler->filter('#profile > div.trn-scont.ftr-overview-grid > div > div:nth-child(3) > div > div:nth-child(2) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('#profile > div.trn-scont.ftr-overview-grid > div > div:nth-child(3) > div > div:nth-child(2) > div.trn-defstat__values > div')->text()
                    : null,
                "kill"
                => $crawler->filter('#profile > div.trn-scont.ftr-overview-grid > div > div:nth-child(3) > div > div:nth-child(3) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('#profile > div.trn-scont.ftr-overview-grid > div > div:nth-child(3) > div > div:nth-child(3) > div.trn-defstat__values > div')->text()
                    : null,
                "kill_die"
                => $crawler->filter('#profile > div.trn-scont.ftr-overview-grid > div > div:nth-child(3) > div > div:nth-child(4) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('#profile > div.trn-scont.ftr-overview-grid > div > div:nth-child(3) > div > div:nth-child(4) > div.trn-defstat__values > div')->text()
                    : null,
            ],
            "solo" => [
                "total"
                => $crawler->filter('#profile > div.ftr-playlist-grid > div.trn-card.trn-card--ftr-blue > div.trn-card__header > span')->count() > 0
                    ? $crawler->filter('#profile > div.ftr-playlist-grid > div.trn-card.trn-card--ftr-blue > div.trn-card__header > span')->text()
                    : null,
                "wins"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(1) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(1) > div.trn-defstat__values > div')->text()
                    : null,
                "win_percentage"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(2) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(2) > div.trn-defstat__values > div')->text()
                    : null,
                "top_ten"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(3) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(3) > div.trn-defstat__values > div')->text()
                    : null,
                "top_twenty_five"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(4) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(4) > div.trn-defstat__values > div')->text()
                    : null,
                "avg_time"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(5) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(5) > div.trn-defstat__values > div')->text()
                    : null,
                "kill"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(6) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(6) > div.trn-defstat__values > div')->text()
                    : null,
                "kill_die"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(7) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(7) > div.trn-defstat__values > div')->text()
                    : null,
                "kill_match"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(8) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(8) > div.trn-defstat__values > div')->text()
                    : null,
                "kill_min"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(9) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(9) > div.trn-defstat__values > div')->text()
                    : null,
                "time_played"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(10) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(10) > div.trn-defstat__values > div')->text()
                    : null,
                "score"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(11) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(11) > div.trn-defstat__values > div')->text()
                    : null,
                "score_match"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(12) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(12) > div.trn-defstat__values > div')->text()
                    : null,
                "score_min"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(13) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-blue > div:nth-child(3) > div > div:nth-child(13) > div.trn-defstat__values > div')->text()
                    : null,
            ],
            "dous" => [
                "total"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div.trn-card__header > span')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div.trn-card__header > span')->text()
                    : null,
                "wins"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(1) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(1) > div.trn-defstat__values > div')->text()
                    : null,
                "win_percentage"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(2) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(2) > div.trn-defstat__values > div')->text()
                    : null,
                "top_ten"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(3) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(3) > div.trn-defstat__values > div')->text()
                    : null,
                "top_twenty_five"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(4) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(4) > div.trn-defstat__values > div')->text()
                    : null,
                "avg_time"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(5) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(5) > div.trn-defstat__values > div')->text()
                    : null,
                "kill"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(6) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(6) > div.trn-defstat__values > div')->text()
                    : null,
                "kill_die"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(7) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(7) > div.trn-defstat__values > div')->text()
                    : null,
                "kill_match"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(8) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(8) > div.trn-defstat__values > div')->text()
                    : null,
                "kill_min"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(9) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(9) > div.trn-defstat__values > div')->text()
                    : null,
                "time_played"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(10) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(10) > div.trn-defstat__values > div')->text()
                    : null,
                "score"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(11) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(11) > div.trn-defstat__values > div')->text()
                    : null,
                "score_match"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(12) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(12) > div.trn-defstat__values > div')->text()
                    : null,
                "score_min"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(13) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-green > div:nth-child(3) > div > div:nth-child(13) > div.trn-defstat__values > div')->text()
                    : null,
            ],
            "squads" => [
                "total"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div.trn-card__header > span')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div.trn-card__header > span')->text()
                    : null,
                "wins"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(1) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(1) > div.trn-defstat__values > div')->text()
                    : null,
                "win_percentage"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(2) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(2) > div.trn-defstat__values > div')->text()
                    : null,
                "top_ten"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(3) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(3) > div.trn-defstat__values > div')->text()
                    : null,
                "top_twenty_five"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(4) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(4) > div.trn-defstat__values > div')->text()
                    : null,
                "avg_time"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(5) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(5) > div.trn-defstat__values > div')->text()
                    : null,
                "kill"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(6) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(6) > div.trn-defstat__values > div')->text()
                    : null,
                "kill_die"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(7) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(7) > div.trn-defstat__values > div')->text()
                    : null,
                "kill_match"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(8) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(8) > div.trn-defstat__values > div')->text()
                    : null,
                "kill_min"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(9) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(9) > div.trn-defstat__values > div')->text()
                    : null,
                "time_played"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(10) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(10) > div.trn-defstat__values > div')->text()
                    : null,
                "score"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(11) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(11) > div.trn-defstat__values > div')->text()
                    : null,
                "score_match"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(12) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(12) > div.trn-defstat__values > div')->text()
                    : null,
                "score_min"
                => $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(13) > div.trn-defstat__values > div')->count() > 0
                    ? $crawler->filter('div#profile div.trn-card.trn-card--ftr-purple > div:nth-child(3) > div > div:nth-child(13) > div.trn-defstat__values > div')->text()
                    : null,
            ],
        ];

        return $data;
    }

    public function getSpecialData(BrowserInterface $browser, DomCrawler $domCrawler, $url, $site)
    {
        if (
            preg_match("/^https:\/\/fortnitetracker\.com\/leaderboards\/all\/Top1\?mode\=all\&page\=[0-9]+/", $browser->getSite()) ||
            preg_match("/^https:\/\/fortnitetracker.com\/leaderboards$/", $browser->getSite())
        ) {
            pinfo("Getting special data", "...");
            if ($domCrawler->filter('#leaderboard > section > div > div.trn-card__content.pb0 > div.trn-pagination-wrapper > ul > li.trn-pagination__item.trn-pagination__item--active + li.trn-pagination__item')->count() > 0) {
                $nextPage =  $domCrawler->filter('#leaderboard > section > div > div.trn-card__content.pb0 > div.trn-pagination-wrapper > ul > li.trn-pagination__item.trn-pagination__item--active + li.trn-pagination__item')->text();
                $nextUrl = replaceParam($browser->getSite(), ["page" => $nextPage]);
                !app(UrlService::class)->checkExist($nextUrl, $site) && app(UrlService::class)->save($nextUrl, $site, $browser->getSite());
                pinfo("Saved", $nextUrl);
                pinfo("Done", "...");
            }
        }
    }

    public function otherConfigOption()
    {
        return [
            "waitForFunction" => [
                "function" => "document.querySelector('.trn-site') != null",
                "polling" => 500,
                "timeout" => 20000
            ],
            "timeout" => 960000,
            "randomUserAgent" => true,
        ];
    }
}
