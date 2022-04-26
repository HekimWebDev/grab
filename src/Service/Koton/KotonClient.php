<?php

namespace Service\Koton;

use Goutte\Client as GoutteClient;
use Service\Koton\Request\CategoryRequest;
use Service\Koton\Request\PriceRequest;
use Service\Koton\Request\ProductRequest;
use Symfony\Component\DomCrawler\Crawler;

class KotonClient
{
    use CategoryRequest;
    use ProductRequest;
    use PriceRequest;

    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://www.koton.com/';
    }

    public function goutteClient()
    {
        return new GoutteClient();
    }

    public function getFromHtml($tag, string $url = '')
    {
        $url = $this->baseUrl . $url;

        $this->response = $this->goutteClient()->request('GET', $url);

        if ($this->response->filter($tag)->count() > 0)
            return $this->response->filter($tag);

        return $this->response;
    }

}
