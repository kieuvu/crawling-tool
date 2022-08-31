<?php

namespace App\Services;

use App\Configs\Site\SiteInterface;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class CrawlerService
{
    public function __construct(public UrlService $urlService)
    {
    }

    public function run(SiteInterface $siteConfig)
    {
        $site = $siteConfig->rootUrl();

        logger()->info("Crawling...:", [$site]);

        $this->init(
            $site,
            $siteConfig->startUrls()
        );

        while ($this->urlService->hasPendingRecords($site)) {
            $pendingRecord    = $this->urlService->getPendingRecord($site);
            $pendingRecordUrl = $pendingRecord->url;

            try {
                if ($siteConfig->isValidUrl($pendingRecordUrl)) {
                    logger()->info("Current Target:", [$pendingRecordUrl]);

                    $html       = file_get_contents($pendingRecordUrl);
                    $domCrawler = new DomCrawler($html);

                    $domCrawler->filter('a')->each(function (DomCrawler $node, $i) use ($siteConfig, $site) {
                        $retrivedUrl = $siteConfig->formatUrl($node->attr('href') ?: "");
                        if (!$this->urlService->checkExist($retrivedUrl, $site)) {
                            $this->urlService->save($retrivedUrl, $site);
                            logger()->info("Saved:", [$retrivedUrl]);
                        }
                        return true;
                    });

                    if ($siteConfig->canBeStored($pendingRecordUrl)) {
                        $data = $siteConfig->getData($domCrawler);
                        $this->urlService->updateData($pendingRecord, $data);
                        logger()->info("Has data:", [$pendingRecordUrl, $data]);
                    }
                }
            } catch (\Exception) {
            }

            $this->urlService->updateStatus($pendingRecord, 1);
        };
    }

    public function init(string $site, array $startUrls)
    {
        logger()->info("Saving Start Point...:", [$site]);
        foreach ($startUrls as $url) {
            if (!$this->urlService->checkExist($url, $site)) {
                $this->urlService->save($url, $site);
                logger()->info("Saved:", [$url]);
            } else {
                logger()->info("Existed:", [$url]);
            }
        }
    }
}
