<?php

namespace Service\Avva\Request;

trait CategoryRequest
{
    public function getCategoriesFromHtml(): array
    {
        $query = $this->getFromHtml('#ResimliMenu1');

        $arr[] = $query->each(function ($node){
            return $node->filter('li a')->each(function ($n){
                return $n->attr('href');
            });
        });

        return $arr[0][0];
    }
}
