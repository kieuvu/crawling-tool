<?php

namespace App\Libs\Browser;

interface BrowserInterface
{
    public function setSite(string $url);
    public function getSite();
    public function getSiteContent();
    public function setTimeout(int $timeout);
    public function getClientBrowser();
}
