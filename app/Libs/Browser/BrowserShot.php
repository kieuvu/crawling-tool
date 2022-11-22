<?php

namespace App\Libs\Browser;

use Spatie\Browsershot\Browsershot as BS;

class BrowserShot extends BrowserAbstract
{
    public function getSiteContent()
    {
        return BS::url($this->url)
            ->noSandbox()
            ->setNodeBinary('/usr/local/bin/node')
            ->setNpmBinary('/home/kieuvu/.npm-global/bin/npm')
            ->setNodeModulePath(base_path('node_modules/'))
            ->waitUntilNetworkIdle()
            ->timeout($this->timeout)
            ->bodyHtml();
    }

    public function getClientBrowser()
    {
        return app(BS::class);
    }
}
