<?php

namespace App\Libs\Browser;

abstract class BrowserAbstract implements BrowserInterface
{
    function __construct(protected string $url = "", protected array $config = [])
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

    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    public function getSite()
    {
        return $this->url;
    }
}
