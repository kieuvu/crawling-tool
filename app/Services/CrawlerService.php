<?php

namespace App\Services;

use App\Configs\Site\SiteInterface;
use App\Libs\Browser\BrowserInterface;
use App\Models\Site;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class CrawlerService
{
    private BrowserInterface $browser;

    public function __construct(
        private UrlService $urlService,
        private SiteService $siteService
    ) {
    }

    public function run(SiteInterface $siteConfig)
    {
        $site = $this->siteService->saveSite($siteConfig->rootUrl());

        foreach ($siteConfig->startPoints() as $url) {
            if (!$this->urlService->checkExist($url, $site)) {
                $this->urlService->save($url, $site);
                p_info("Saved", $url);
                p_dash();
            }
        }

        $this->getSiteInfo($site);

        while ($this->urlService->hasPendingRecords($site)) {
            $pendingRecord    = $this->urlService->getPendingRecord($site);
            $pendingRecordUrl = $pendingRecord->url;

            try {
                p_info("Current Target", $pendingRecordUrl);

                $this->browser
                    ->setSite($pendingRecordUrl)
                    ->setConfig($siteConfig->otherConfigOption());

                $html = $this->browser->getSiteContent();

                $domCrawler = new DomCrawler($html);

                $siteConfig->getSpecialData($this->browser, $domCrawler, $pendingRecordUrl, $site);

                if ($siteConfig->canBeStored($pendingRecordUrl) && is_null($pendingRecord->data_file)) {
                    $data = $siteConfig->getData($domCrawler);
                    $this->urlService->updateData($pendingRecord, $data, $site);
                    p_info("Has data", $data['title']);
                    print_r($data);
                }

                $domCrawler->filter('a')->each(function (DomCrawler $node) use ($siteConfig, $site, $pendingRecordUrl) {
                    $retrievedUrl = $siteConfig->formatUrl($node->attr('href') ?: "");
                    if (
                        $siteConfig->isValidUrl($retrievedUrl) &&
                        !$this->urlService->checkExist($retrievedUrl, $site)
                    ) {
                        $this->urlService->save($retrievedUrl, $site, $pendingRecordUrl);
                        p_info("Saved", $retrievedUrl);
                        return true;
                    }
                    return false;
                });

                $this->urlService->updateStatus($pendingRecord, 1, $site);
            } catch (\Throwable $ex) {
                $this->urlService->updateStatus($pendingRecord, -2, $site);
                logger()->error($ex->getMessage());
                p_info("Failed");
            }

            p_dash();
        }
    }

    public function setBrowser(BrowserInterface $browser)
    {
        $this->browser = $browser;
        return $this;
    }

    public function getSiteInfo(Site $site)
    {
        p_info("Crawling", $site->site);
        p_info("Crawled", "{$site->crawled} / {$site->urls}");
        p_info("Has data", $site->has_data ?: "0");
        p_dash();
    }
}
