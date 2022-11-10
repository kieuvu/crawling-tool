<?php

namespace App\Services\Browser;

interface BrowserInterface
{
    public function setSite(string $url);
    public function getSiteContent();
}
