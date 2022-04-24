<?php

namespace Service\Koton;

use Goutte\Client as GoutteClient;
use Service\Koton\Request\CategoryRequest;
use Service\Koton\Request\ProductRequest;
use Symfony\Component\DomCrawler\Crawler;

class KotonClient
{
    use CategoryRequest;
    use ProductRequest;

    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://www.koton.com/';
//        $this->basUrl = config('grabconfig.Koton.base_url');
//        dd($this->basUrl);
    }

    public function goutteClient()
    {
        return new GoutteClient();
    }

    public function getFromHtml($tag, string $url = '')
    {
        $url = $this->baseUrl . $url;

        $this->response = $this->goutteClient()->request('GET', $url);

        return $this->response->filter($tag);
    }

}
