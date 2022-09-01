<?php

namespace App\Services;

use App\Configs\Site\SiteInterface;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Spatie\Browsershot\Browsershot;

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
            $siteConfig->startPoints()
        );

        while ($this->urlService->hasPendingRecords($site)) {
            $pendingRecord    = $this->urlService->getPendingRecord($site);
            $pendingRecordUrl = $pendingRecord->url;

            try {
                logger()->info("Current Target:", [$pendingRecordUrl]);

                $html = Browsershot::url($pendingRecordUrl)->bodyHtml();

                $domCrawler = new DomCrawler($html);

                if ($siteConfig->canBeStored($pendingRecordUrl)) {
                    $data = $siteConfig->getData($domCrawler);
                    $this->urlService->updateData($pendingRecord, $data);
                    logger()->info("Has data:", [$pendingRecordUrl, $data]);
                }

                $domCrawler->filter('a')->each(function (DomCrawler $node, $i) use ($siteConfig, $site) {
                    $retrivedUrl = $siteConfig->formatUrl($node->attr('href') ?: "");
                    if ($siteConfig->isValidUrl($retrivedUrl)) {
                        if (!$this->urlService->checkExist($retrivedUrl, $site)) {
                            $this->urlService->save($retrivedUrl, $site);
                            logger()->info("Saved:", [$retrivedUrl]);
                        }
                        return true;
                    }
                    return false;
                });
            } catch (\Exception $ex) {
                logger()->error($ex->getMessage());
            }

            $this->urlService->updateStatus($pendingRecord, 1);
        };
    }

    public function init(string $site, array $startPoints)
    {
        logger()->info("Saving Start Point...:", [$site]);
        foreach ($startPoints as $url) {
            if (!$this->urlService->checkExist($url, $site)) {
                $this->urlService->save($url, $site);
                logger()->info("Saved:", [$url]);
            } else {
                logger()->info("Existed:", [$url]);
            }
        }
    }
}
