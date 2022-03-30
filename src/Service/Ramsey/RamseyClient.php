<?php

namespace Service\Ramsey;


use Goutte\Client as GoutteClient;

class RamseyClient
{
    protected string $url;

    protected Crawler $response;

    protected Crawler $response;

    protected mixed $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('grabconfig.Ramsey.base_url');
    }

    public function goutteClien()
    {
        return new GoutteClient();
    }

    public function getFromHtml($tag, string $url = ''): Crawler
    {
        $url = $this->baseUrl . $url;

        $this->response = $this->goutteClient()->request('GET', $url);

        return $this->response->filter($tag);
    }
}
