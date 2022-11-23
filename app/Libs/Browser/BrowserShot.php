<?php

namespace App\Libs\Browser;

use Spatie\Browsershot\Browsershot as BS;

class BrowserShot extends BrowserAbstract
{
    public function getSiteContent()
    {
        $config =  BS::url($this->url)
            ->noSandbox()
            ->setNodeBinary('/usr/local/bin/node')
            ->setNpmBinary('/home/kieuvu/.npm-global/bin/npm')
            ->setNodeModulePath(base_path('node_modules/'))
            ->waitUntilNetworkIdle();

        if (array_key_exists("timeout", $this->config)) {
            $config->timeout($this->config["timeout"]);
        }

        if (array_key_exists("randomUserAgent", $this->config) && $this->config["randomUserAgent"]) {
            $config->userAgent(randomUserAgent());
        }

        if (array_key_exists("waitForFunction", $this->config)) {
            $config->waitForFunction(
                $this->config["waitForFunction"]["function"],
                $this->config["waitForFunction"]["polling"],
                $this->config["waitForFunction"]["timeout"],
            );
        }

        return $config->bodyHtml();
    }

    public function getClientBrowser()
    {
        return app(BS::class);
    }
}
