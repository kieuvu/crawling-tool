<?php

namespace App\Libs\Browser;

abstract class BrowserAbstract implements BrowserInterface
{
    function __construct(protected string $url = "")
    {
    }

    public function setSite(string $url)
    {
        $this->url = $url;
        return $this;
    }

    public function getSiteContent()
    {
        return "";
    }
}
