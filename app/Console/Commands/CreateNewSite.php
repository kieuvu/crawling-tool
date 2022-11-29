<?php

namespace App\Console\Commands;

use Illuminate\Filesystem\Filesystem;

use Illuminate\Console\Command;

class CreateNewSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:file {--file= : file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = app_path();

        $fileName = fileNameSanitizer($this->option('file'));
        $fileExtension = "php";
        $filePath = "{$path}/Configs/Site/Extends/{$fileName}.{$fileExtension}";

        if ($fileName === '' || is_null($fileName) || empty($fileName)) {
            return $this->error('File Name Invalid..!');
        }

        $fileContent = "<?php

namespace App\Configs\Site\Extends;

use App\Configs\Site\SiteAbstract;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class {$fileName} extends SiteAbstract
{
    public function rootUrl(): string
    {
        return '';
    }

    public function startPoints(): array
    {
        return [];
    }
}";

        if ($this->files->exists($filePath)) {
            $this->error("{$fileName} Already exists!");
        } else {
            $this->files->put($filePath, $fileContent);
            $this->info("{$fileName} generated!");
        }

        return 0;
    }
}
