<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrawlUrl extends Model
{
    use HasFactory;

    protected $fillable  =  [
        "site",
        "url",
        "url_hash",
        "data",
    ];
}
