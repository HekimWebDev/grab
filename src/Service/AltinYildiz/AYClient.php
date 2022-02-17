<?php

namespace Service\AYClassic;

use Goutte\Client;
use JetBrains\PhpStorm\Pure;
use Service\AYClassic\Requests\GetCategories;
use Symfony\Component\DomCrawler\Crawler;

class AYClient
{
    protected string $url;
    protected Client $client;
    protected Crawler $response;

    public function __construct()
    {
        $this->url = config('grabconfig.AYConfig.base_url');
        $this->client = new Client();
        $this->response = $this->client->request('GET', $this->url);
        return $this;
    }

    public function getCategories($bool = true): GetCategories|string
    {
    }

    public function getContent($tag, $return = 'text'): string
    {
        return $this->response->filter($tag)->$return();
    }

    /**
     * @return Crawler
     */
    public function getResponse(): Crawler
    {
        return $this->response;
    }

}
