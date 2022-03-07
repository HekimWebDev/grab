<?php

namespace Service\AltinYildiz\Requests;

use Goutte\Client;
use phpDocumentor\Reflection\Types\Boolean;

trait CategoryRequests
{
    // public function getJsonCategories(): bool|string
    // {
    //     $data = [
    //         '/orme-esnek-360-gomlek-c-2802',
    //         '/atayaka-gomlek-c-2824',
    //         'desenli-gomlek-c-2741',
    //     ];

    //     return json_encode($data);
    // }

    // public function getCategories($url):array
    // {
    //     $url = $this->baseURL . '/' . $url;

    //     return $this->grab($url);
    // }

    public function getParentCategories($url) : array
    {
        $data = [];

        $filter = '#leftCategoryFilter li a';

        $crawler = $this->goutteClient()->request('GET', $url);

        $data['name'] = $crawler->filter($filter)->each(function ($node){
            return $node->text();
        });

        $data['url'] = $crawler->filter($filter)->each(function ($node){
            return $node->attr('href');
        });

        return $data;
    }

    public function getSubCategories($url):array
    {
        $data = [];

        $filter = '#leftCategoryFilter li.active';

        $url = $this->baseURL . '/' . $url;

        $crawler = $this->goutteClient()->request('GET', $url);

        //  get names
        $data['name'] = $crawler->filter($filter)
            ->last()
            ->filter('ul a')
            ->each(function ($node){
                return $node->text();
            });

        // get urls
        $data['url'] = $crawler->filter($filter)
            ->last()
            ->filter('ul a')
            ->each(function ($node){
                return $node->attr('href');
            });

        return $data;
    }
}
