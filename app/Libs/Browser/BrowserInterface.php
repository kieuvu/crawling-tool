<?php

namespace App\Libs\Browser;

interface BrowserInterface
{
    public function setSite(string $url);
    public function getSite();
    public function getSiteContent();
    public function setConfig(int $config);
    public function getClientBrowser();
}
