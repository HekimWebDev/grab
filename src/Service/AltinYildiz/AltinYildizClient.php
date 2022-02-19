<?php

namespace Service\AltinYildiz;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class AltinYildizClient
{
    protected string $url;
    protected Client $client;
    protected Crawler $response;

    public function __construct()
    {
        $this->url = config('grabconfig.AYConfig.base_url');
        $this->client = new Client();
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
    public function getResponse($tag, string $url = ''): Crawler
    {
        $url = $this->url.'/'.$url;
        $this->response = $this->client->request('GET', $url);
        return $this->getContent($tag);
    }

}
