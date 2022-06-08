<?php

namespace Service\Avva\Request;

trait CategoryRequest
{
//    gets category urls
    public function getCategoriesFromHtml(): array
    {
        $query = $this->getFromHtml('#ResimliMenu1');

        $arr[] = $query->each(function ($node){
            return $node->filter('li a')->each(function ($n){
                return $n->attr('href');
            });
        });

        return $arr;
    }

//    gets PageId for each category
    public function getPageIdFromHtml(string $categoryUrl): null|int
    {
        $query = $this->getFromHtml('div.personaclick-recommend.personaclick-kategori-populer-urunler', $categoryUrl);

        $categoryId = $query->attr('data-recommender-category');

        return $categoryId;
    }
}
