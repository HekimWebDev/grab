<?php

namespace Service\AltinYildiz\Requests;

use Goutte\Client;
use phpDocumentor\Reflection\Types\Boolean;

class Categories
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
            'orme-esnek-360-gomlek-c-2802',
            'atayaka-gomlek-c-2824',
            'desenli-gomlek-c-2741',
        ];
        return json_encode($data);
        // Json formatda gaytaryp bermek (productlary almak ucin)
    }

    protected function getCategories($url):array
    {
        $data = [];
        $crawler = $this->client->request('GET', $url);
        $data[] = $crawler->filter('#leftCategoryFilter li a')->each(function ($node){
                        return $node->text() . '   ' . $node->attr('href');
                    });
        return $data;
    }

    public function getSubCategories(String $name, $url, $tree = false):array
    {
        $data = [];
//        dd(true);
        if ($tree)
        {
            $filter = '#leftCategoryFilter ul .active li.active a';
        } else {
            $filter = '#leftCategoryFilter ul li.active a';

        }

        $url = $this->baseURL . '/' . $url;

        $crawler = $this->client->request('GET', $url);
        $data[] = $crawler->filter($filter)->each(function ($node, $name){
                           return $node->text() . '   ' . $node->attr('href');
                    });
        return $data;
    }


    public function getClothesCategories():array
    {
        $url = $this->baseURL . '/' . config('grabconfig.AYConfig.parent_categories.Giyim');
            return $this->getCategories($url);
    }

    public function getShoesCategories():array
    {
        $url = $this->baseURL . '/' . config('grabconfig.AYConfig.parent_categories.Ayakkabı');
        return $this->getCategories($url);
    }

    public function getAccessoriesCategories():array
    {
        $url = $this->baseURL . '/' . config('grabconfig.AYConfig.parent_categories.Aksesuar');
        return $this->getCategories($url);
    }
}
