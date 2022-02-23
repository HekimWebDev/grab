<?php

namespace Service\AltinYildiz\Requests;

use Goutte\Client;
use phpDocumentor\Reflection\Types\Boolean;

class CategoryClient
{
    protected $client;
    protected $baseURL;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseURL = config('grabconfig.AYConfig.base_url');
    }

    protected function getHtmlCategories(): string
    {
//        return html-den almak (hepde-de alyp durar yaly)
        return '';
    }

    public function getJsonCategories(): bool|string
    {
        $data = [
            '/orme-esnek-360-gomlek-c-2802',
            '/atayaka-gomlek-c-2824',
            'desenli-gomlek-c-2741',
        ];
        return json_encode($data);
        // Json formatda gaytaryp bermek (productlary almak ucin)
    }

//    Ysmayyl.dev

//    public function getClothesCategories():array
//    {
//        $url = $this->baseURL . '/' . config('grabconfig.AYConfig.parent_categories.Giyim');
//            return $this->getCategories($url);
//     }

    public function getCategories($url):array
    {
        $url = $this->baseURL . '/' .$url;
        return $this->Grab($url);
    }

    protected function Grab($url):array
    {
        $data = [];
        $filter = '#leftCategoryFilter li a';
        $crawler = $this->client->request('GET', $url);
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

        $crawler = $this->client->request('GET', $url);

//        get names
        $data['name'] = $crawler->filter($filter)
            ->last()
            ->filter('ul a')
            ->each(function ($node){
                return $node->text();
            });

//        get urls
        $data['url'] = $crawler->filter($filter)
            ->last()
            ->filter('ul a')
            ->each(function ($node){
                return $node->attr('href');
            });

//        dd($data);
        return $data;
    }

//    trait
}
