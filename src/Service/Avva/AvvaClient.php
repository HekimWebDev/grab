<?php

namespace Service\Avva;

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use Service\Avva\Request\CategoryRequest;
use Service\Avva\Request\PriceRequest;
use Service\Avva\Request\ProductRequest;

class AvvaClient
{
    use CategoryRequest;
    use ProductRequest;
    use PriceRequest;

    protected $baseUrl;
    private $response;

    public function __construct()
    {
        $this->baseUrl = 'https://www.avva.com.tr/';
    }

    public function goutteClient(): GoutteClient
    {
        return new GoutteClient();
    }

    public function guzzleClient(): GuzzleClient
    {
        return new GuzzleCLient();
    }

    public function getFromHtml($tag, string $url = '')
    {
        $url = $this->baseUrl . $url;

        $this->response = $this->goutteClient()->request('GET', $url);

        if ($this->response->filter($tag)->count() > 0)
            return $this->response->filter($tag);

        return $this->response;
    }

    public function getFromAPI(string $url)
    {
        $url = $this->baseUrl . $url;

//        var_dump($url);

        $this->response = $this->guzzleClient()->request('GET', $url, [
            'http_errors' => false,
            'headers' => [
                'authority' => 'www.avva.com.tr',
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36'
            ],
        ]);
//        $this->response = $this->guzzleClient()->request('GET', $url);

        return $this->response;
    }
}
