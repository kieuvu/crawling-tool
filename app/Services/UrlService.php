<?php

namespace App\Services;

use App\Models\CrawlUrl;
use App\Models\Site;

class UrlService
{
    public function __construct(private SiteService $siteService)
    {
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

    public function updateData(CrawlUrl $record, $data, Site $site): bool
    {
        $this->siteService->updateDataCount($site);
        $record->data = json_encode($data);
        return $record->save();
    }

    public function updateStatus(CrawlUrl $record, int $status, Site $site)
    {
        $this->siteService->updatedCrawledCount($site);
        $record->visited = $status;
        return $record->save();
    }
}
