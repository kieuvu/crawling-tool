<?php

namespace App\Services;

use App\Models\CrawlUrl;
use App\Models\Site;

class UrlService
{
    public function __construct(
        private SiteService $siteService,
        private StorageService $storageService
    ) {
    }

    public function checkExist(string $url, Site $site): bool
    {
        return CrawlUrl::where('site', $site->id)
            ->where('url_hash', [md5($url)])
            ->exists();
    }

    public function hasPendingRecords(Site $site): bool
    {
        return CrawlUrl::where('site', $site->id)
            ->where('visited', -1)
            ->exists();
    }

    public function getPendingRecord(Site $site): CrawlUrl
    {
        return CrawlUrl::where('site', $site->id)
            ->where('visited', -1)
            ->first();
    }

    public function save(string $url, Site $site): CrawlUrl
    {
        $this->siteService->updateUrlCount($site);
        return CrawlUrl::create([
            'site'     => $site->id,
            'url'      => $url,
            'url_hash' => md5($url),
        ]);
    }

    public function updateData(CrawlUrl $record, $data, Site $site): CrawlUrl
    {
        $dataFile = $this->storageService->createDataFile($record, $data);
        $record->data_file = $dataFile;
        $record->save();
        $this->siteService->updateDataCount($site);
        return $record;
    }

    public function updateStatus(CrawlUrl $record, int $status, Site $site): CrawlUrl
    {
        $record->visited = $status;
        $record->save();
        $this->siteService->updatedCrawledCount($site);
        return $record;
    }
}
