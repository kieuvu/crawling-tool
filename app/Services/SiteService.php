<?php

namespace App\Services;

use App\Models\Site;

class SiteService
{
    public function saveSite(string $site)
    {
        $siteExist = Site::where(['site' => $site])->first();
        if (!$siteExist) {
            return Site::create([
                'site' => $site,
            ]);
        };
        return $siteExist;
    }

    public function updateUrlCount(Site $site)
    {
        return $site->increment('urls');
    }

    public function updatedCrawledCount(Site $site)
    {
        return $site->increment('crawled');
    }

    public function updateDataCount(Site $site)
    {
        return $site->increment('has_data');
    }
}
