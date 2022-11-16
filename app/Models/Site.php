<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    protected $fillable  =  [
        "site",
        "urls",
        "has_data",
        "crawled",
    ];

    public function crawl_urls(): HasMany
    {
        return $this->hasMany(\App\Models\CrawlUrl::class, 'site', 'id');
    }
}
