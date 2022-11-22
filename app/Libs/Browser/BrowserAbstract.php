<?php

namespace App\Libs\Browser;

abstract class BrowserAbstract implements BrowserInterface
{
    function __construct(protected string $url = "", protected int $timeout = 60)
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

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function getSite()
    {
        return $this->url;
    }
}
