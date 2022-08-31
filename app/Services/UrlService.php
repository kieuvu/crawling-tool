<?php

namespace App\Services;

use App\Models\CrawlUrl;

class UrlService
{
    public function __construct(public CrawlUrl $crawlUrl)
    {
    }

    public function checkExist(string $url, string $site): bool
    {
        return $this->crawlUrl
            ->where('site', $site)
            ->where('url_hash', [md5($url)])
            ->exists();
    }

    public function hasPendingRecords($site): bool
    {
        return $this->crawlUrl
            ->where('site', $site)
            ->where('visited', -1)
            ->exists();
    }

    public function getPendingRecord($site): CrawlUrl
    {
        return $this->crawlUrl
            ->where('site', $site)
            ->where('visited', -1)
            ->first();
    }

    public function save(string $url, string  $site): CrawlUrl
    {
        return $this->crawlUrl->create([
            'site'     => $site,
            'url'      => $url,
            'url_hash' => md5($url),
        ]);
    }

    public function updateData(CrawlUrl $record, $data): bool
    {
        $record->data = json_encode($data);
        return $record->save();
    }

    public function updateStatus(CrawlUrl $record, int $status)
    {
        $record->visited = $status;
        return $record->save();
    }
}
