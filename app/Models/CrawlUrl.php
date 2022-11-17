<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrawlUrl extends Model
{
    use HasFactory;

    protected $fillable  =  [
        "site",
        "url",
        "url_hash",
        "data_file",
        "visited",
    ];

    function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site');
    }
}
