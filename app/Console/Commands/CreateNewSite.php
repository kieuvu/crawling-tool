<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateNewSite extends Command
{
    protected $signature = 'crawl-make:file {fileName}';

    public function handle()
    {
        $fileName = $this->argument('fileName');
        $stub     = str_replace('{{fileName}}', $fileName, $this->getConfigFileStub());
        $filePath = $this->getConfigFilePath($fileName);

        if (file_exists($filePath)) {
            $this->error('Config file already exists!');
            return;
        }

        file_put_contents($filePath, $stub);
        $this->info('Config file created successfully!');
    }

    private function getConfigFileStub()
    {
        return file_get_contents(base_path('stubs/new-site.stub'));
    }

    private function getConfigFilePath($fileName)
    {
        return app_path("Configs/Site/Extends/{$fileName}.php");
    }
}
