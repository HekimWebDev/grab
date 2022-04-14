<?php

namespace Service\Mavi;


use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;

class MaviClient
{
    protected string $url;

    protected $baseUrl;

    private $response;

    public function __construct()
    {
//        $this->baseUrl= config('grabconfig.Mavi.base_url');
        $this->baseUrl= 'https://www.mavi.com';
    }

    public function goutteClient(): GoutteClient
    {
        return new GoutteClient();
    }

    public function guzzleClient(): GuzzleClient
    {
        return new GuzzleClient();
    }

    public function getFromHtml($tag, string $url = '')
    {
        $url = $this->baseUrl . $url;

        $this->response = $this->goutteClient()->request('GET', $url);

        return $this->response->filter($tag);
    }
}