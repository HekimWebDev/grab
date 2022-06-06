<?php

namespace Service\Avva;

use Goutte\Client as GoutteClient;
use Service\Avva\Request\CategoryRequest;

class AvvaClient
{
    use CategoryRequest;

    protected $baseUrl;
    private $response;

    public function __construct()
    {
        $this->baseUrl = 'https://www.avva.com.tr/';
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
