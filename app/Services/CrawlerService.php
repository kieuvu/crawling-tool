<?php

namespace App\Services;

use App\Configs\Site\SiteInterface;
use App\Libs\Browser\BrowserInterface;
use App\Libs\Console\BeautyEcho;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class CrawlerService
{
    private BrowserInterface $browser;

    public function __construct(private UrlService $urlService)
    {
    }

    public function run(SiteInterface $siteConfig)
    {
        $site = $siteConfig->rootUrl();

        pinfo("Crawling", $site);

        $this->init(
            $site,
            $siteConfig->startPoints()
        );

        while ($this->urlService->hasPendingRecords($site)) {
            $pendingRecord    = $this->urlService->getPendingRecord($site);
            $pendingRecordUrl = $pendingRecord->url;

            try {
                pinfo("Current Target", $pendingRecordUrl);

                $html = $this->browser
                    ->setSite($pendingRecordUrl)
                    ->getSiteContent();

                $domCrawler = new DomCrawler($html);

                if ($siteConfig->canBeStored($pendingRecordUrl)) {
                    $data = $siteConfig->getData($domCrawler);
                    $this->urlService->updateData($pendingRecord, $data);
                    pinfo("Has data", $data['title']);
                }

                $domCrawler->filter('a')->each(function (DomCrawler $node, $i) use ($siteConfig, $site) {
                    $retrivedUrl = $siteConfig->formatUrl($node->attr('href') ?: "");
                    if ($siteConfig->isValidUrl($retrivedUrl)) {
                        if (!$this->urlService->checkExist($retrivedUrl, $site)) {
                            $this->urlService->save($retrivedUrl, $site);
                            pinfo("Saved", $retrivedUrl);
                        }
                        return true;
                    }
                    return false;
                });
            } catch (\Exception $ex) {
                logger()->error($ex->getMessage());
            }

            $this->urlService->updateStatus($pendingRecord, 1);
            pdash();
        };
    }

    public function setBrowser(BrowserInterface $browser)
    {
        $this->browser = $browser;
        return $this;
    }

    public function init(string $site, array $startPoints)
    {
        pinfo("Saving Start Point", $site);
        foreach ($startPoints as $url) {
            if (!$this->urlService->checkExist($url, $site)) {
                $this->urlService->save($url, $site);
                pinfo("Saved", $url);
            } else {
                pinfo("Existed", $url);
            }
        }
    }
}
