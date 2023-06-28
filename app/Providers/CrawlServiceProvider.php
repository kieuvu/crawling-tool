<?php

namespace App\Providers;

use App\Libs\Browser\BrowserInterface;
use App\Libs\Browser\BrowserShot;
use App\Services\CrawlerService;
use Illuminate\Support\ServiceProvider;

class CrawlServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BrowserInterface::class, BrowserShot::class);

        $this->app->bind('crawl-service', function () {
            return app(CrawlerService::class);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
