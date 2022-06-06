<?php

namespace Service\Ramsey\Request;

trait CategoryRequest
{
    public function getCategoriesFromHtml(): array
    {
        $query = $this->getFromHtml('#mainMenu li div.inner');

        $arr[] = $query->each(function ($node){
            return $node->filter('li a')->each(function ($n){
                return $n->attr('href');
            });
        });

        return $arr[0];
    }
}
