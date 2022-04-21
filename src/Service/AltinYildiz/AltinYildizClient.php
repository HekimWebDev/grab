<?php

namespace Service\AltinYildiz;

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
//use Service\AltinYildiz\Requests\CategoryRequests;
use Service\AltinYildiz\Requests\PriceRequest;
use Service\AltinYildiz\Requests\ProductRequests;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AltinYildizClient
{
    use ProductRequests;
    use PriceRequest;

    protected string $url;

    protected Crawler $response;

    protected mixed $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('grabconfig.AYConfig.base_url');
    }

    public function goutteClient(): GoutteClient
    {
        return new GoutteClient();
    }

    public function guzzleClient(): GuzzleClient
    {
        return new GuzzleClient();
    }

    private function getContent($tag): Crawler
    {
        return $this->response->filter($tag);
    }

    /**
     * @param $tag
     * @param string $url
     * @return Crawler
     */
    public function getFromHTML($tag, string $url = ''): Crawler
    {
        $url = $this->baseUrl.'/'.$url;

        $this->response = $this->goutteClient()->request('GET', $url);

        return $this->response->filter($tag);
    }



}