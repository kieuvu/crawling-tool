<?php

namespace App\Services;

use App\Configs\Site\SiteInterface;
use App\Libs\Browser\BrowserInterface;
use App\Libs\Console\EchoCli;
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

        logger()->info("Crawling...:", [$site]);
        EchoCli::Info("Crawling", $site);

        $this->init(
            $site,
            $siteConfig->startPoints()
        );

        while ($this->urlService->hasPendingRecords($site)) {
            $pendingRecord    = $this->urlService->getPendingRecord($site);
            $pendingRecordUrl = $pendingRecord->url;

            try {
                logger()->info("Current Target:", [$pendingRecordUrl]);
                EchoCli::Info("Current Target", $pendingRecordUrl);

                $html = $this->browser->setSite($pendingRecordUrl)->getSiteContent();

                $domCrawler = new DomCrawler($html);

                if ($siteConfig->canBeStored($pendingRecordUrl)) {
                    $data = $siteConfig->getData($domCrawler);
                    $this->urlService->updateData($pendingRecord, $data);
                    logger()->info("Has data:", [$pendingRecordUrl, $data]);
                    EchoCli::Info("Has data", $data['title']);
                }

                $domCrawler->filter('a')->each(function (DomCrawler $node, $i) use ($siteConfig, $site) {
                    $retrivedUrl = $siteConfig->formatUrl($node->attr('href') ?: "");
                    if ($siteConfig->isValidUrl($retrivedUrl)) {
                        if (!$this->urlService->checkExist($retrivedUrl, $site)) {
                            $this->urlService->save($retrivedUrl, $site);
                            logger()->info("Saved:", [$retrivedUrl]);
                            EchoCli::Info("Saved", $retrivedUrl);
                        }
                        return true;
                    }
                    return false;
                });
            } catch (\Exception $ex) {
                logger()->error($ex->getMessage());
            }

            $this->urlService->updateStatus($pendingRecord, 1);
            EchoCli::Dash();
        };
    }

    public function setBrowser(BrowserInterface $browser)
    {
        $this->browser = $browser;
        return $this;
    }

    public function init(string $site, array $startPoints)
    {
        logger()->info("Saving Start Point...:", [$site]);
        EchoCli::Info("Saving Start Point", $site);
        foreach ($startPoints as $url) {
            if (!$this->urlService->checkExist($url, $site)) {
                $this->urlService->save($url, $site);
                logger()->info("Saved:", [$url]);
                EchoCli::Info("Saved", $url);
            } else {
                logger()->info("Existed:", [$url]);
                EchoCli::Info("Existed", $url);
            }
        }
    }
}
