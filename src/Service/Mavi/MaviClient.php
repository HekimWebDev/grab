<?php

namespace Service\Mavi;


use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use phpDocumentor\Reflection\Types\Integer;
use Service\Mavi\Request\CategoryRequest;
use Service\Mavi\Request\PriceRequest;
use Service\Mavi\Request\ProductRequest;

class MaviClient
{
    use CategoryRequest;
    use ProductRequest;
    use PriceRequest;

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

    /**
     * @return GuzzleClient
     */
    public function guzzleClient(): GuzzleClient
    {
        return new GuzzleClient();
    }

    public function getFromAPI(string $url)
    {
        $url = $this->baseUrl . $url;

        $this->response = $this->guzzleClient()->request('GET', $url, ['http_errors' => false]);

        return $this->response;
    }

    public function getFromHtml($tag, string $url = '')
    {
        $url = $this->baseUrl . $url;

        $this->response = $this->goutteClient()->request('GET', $url);

        return $this->response->filter($tag);
    }
}