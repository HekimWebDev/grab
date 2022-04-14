<?php

namespace Service\Mavi\Request;


trait CategoryRequest
{
    public function getCategories()
    {
        $query = $this->getFromHtml('.drop-one.js-menu-item', '/kadin/c/1');

        $data[] = $query
            ->each(function ($node) {

                $response['url'] = $node->filter('.links.header__navbar__links.js-navbar-category')->attr('href');

                $name = $node->filter('a')->first()->attr('data-category-name');

                $arr[] = $node->filter('.dropdown-menu__content')->each(function ($n){

                    $name = $n->filter('p')->first()->attr('data-category-name');

                    $result[$name] = $n->filter('.dropdown-menu__list li a')->each(function ($q){

                        $name = $q->attr('data-category-name');
                        $result[$name] = $q->attr('href');

                        return $result;
                    });

                    return $result;
                });
//                dd($arr[0]);
                foreach ($arr[0] as $item){
                    $response = array_merge($response, $item);
                }


                return $response;
            });

        return $data[0];
    }
}