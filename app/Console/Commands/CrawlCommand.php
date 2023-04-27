<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CrawlerService;
use App\Configs\Site\SiteMapping;
use App\Libs\Browser\BrowserShot;

class CrawlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl {--site= : site} {--list= : list}';

    /**
     * The console command description.
     *
     * @var string
     */

    public function handle()
    {
        $site = $this->option('site');
        $list = explode(",", $this->option('list'));

        try {
            app(CrawlerService::class)
                ->setBrowser((new BrowserShot()))
                ->run(SiteMapping::getSiteConfig($site)->setStartPoint($list));
        } catch (\Throwable $ex) {
            $this->error($ex->getMessage());
            $this->info("\nExisting :");
            SiteMapping::show();
            echo "\n";
        }

        return true;
    }
}
