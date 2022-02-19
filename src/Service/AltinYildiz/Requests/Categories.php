<?php

namespace Service\AltinYildiz\Requests;

use Goutte\Client;

class Categories
{
    protected $client;
    protected $baseURL;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseURL = config(grabconfig.AYConfig.base_url);
    }

    public function getHtmlCategories(): string
    {
//        return html-den almak (hepde-de alyp durar yaly)
        return null;
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

    public function getClothesCategories():array
    {
        $arr = [];
        $url = config(grabconfig.AYConfig.parent_categories.Giyim);
        $crawler = $this->client->request('Get', $this->baseURL . $url);

        $arr[] = $this->client->getResponse('#leftCategoryFilter li a')
                              ->each(function ($node){
                                  return $node->text() . '   ' . $node->attr('href');
                              });

        return $arr;
    }
}
