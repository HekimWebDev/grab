<?php

namespace Service\Ramsey;

use Goutte\Client as GoutteClient;
use Illuminate\Support\Str;
use Service\Ramsey\Request\PriceRequest;
use Service\Ramsey\Request\ProductRequest;

class RamseyClient
{
    use ProductRequest;
    use PriceRequest

    protected string $url;

    protected mixed $baseUrl;

    private $response;

    public function __construct()
    {
        $this->baseUrl = config('grabconfig.Ramsey.base_url');
    }

    public function goutteClient(): GoutteClient
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
