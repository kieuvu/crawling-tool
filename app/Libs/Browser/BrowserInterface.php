<?php

namespace App\Libs\Browser;

interface BrowserInterface
{
    public function setSite(string $url);
    public function getSiteContent();
}
