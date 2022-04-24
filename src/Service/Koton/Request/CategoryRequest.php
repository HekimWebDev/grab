<?php

namespace Service\Koton\Request;


trait CategoryRequest
{
    public function getCategoriesFromHtml()
    {
        $query = $this->getFromHtml('.main-nav-item', 'tr/');

        $arr[] = $query->each(function ($node){
            return $node->filter('ul a')->each(function ($n){
                return $n->attr('href');
            });
        });

        return $arr;
    }
}
