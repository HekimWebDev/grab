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

    public function getContent($tag, $return = 'text'): string
    {
        return $this->response->filter($tag)->$return();
    }

    /**
     * @param string $url
     * @return AltinYildizClient
     */
    public function getResponse(string $url = ''): AltinYildizClient
    {
        $url = $this->url.'/'.$url;
        $this->response = $this->client->request('GET', $url);
        return $this;
    }

}
