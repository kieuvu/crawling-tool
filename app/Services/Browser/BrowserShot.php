<?php

namespace App\Services\Browser;

use Spatie\Browsershot\Browsershot as BS;

class BrowserShot extends BrowserAbstract
{
    public function getSiteContent()
    {
        return BS::url($this->url)->bodyHtml();
    }
}
