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
        $this->url = config('AYConfig.gateways.base_url');
        $this->client = new Client();
        $this->response = $this->client->request('GET', $this->url);
        return $this;
    }

    public function getCategories($bool = true): GetCategories|string
    {
        if ($bool){
            return 'json';
        }else{
            $res = new GetCategories();
            return $res->getHtmlCategories();
        }
    }

    public function getContent($tag, $return = 'text'): string
    {
        return $this->response->filter($tag)->$return();
    }

}
