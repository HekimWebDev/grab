<?php

namespace Service\AltinYildiz\Requests;

trait CategoryRequest
{
    public function getParentCategories($url) : array
    {
        $data = [];

        $url = $this->baseUrl . '/' . $url;
//        dump($url);
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

        $url = $this->baseUrl . '/' . $url;
//        dump($url);

        $filter = '#leftCategoryFilter li.active';

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
