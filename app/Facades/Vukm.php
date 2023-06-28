<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Vukm extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'crawl-service';
    }
}
