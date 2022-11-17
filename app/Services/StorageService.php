<?php

namespace App\Services;

use App\Models\CrawlUrl;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class StorageService
{
    public static function createDataFile(CrawlUrl $record, $data)
    {
        $folder = Carbon::now()->format("Y/m/d");
        $filename = "{$record->id}.json";
        Storage::disk(config("app.crawl_storage"))->put("{$folder}/{$filename}", json_encode($data));
        return "{$folder}/{$filename}";
    }
}
