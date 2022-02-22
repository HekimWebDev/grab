<?php

namespace Domains\AltinYildiz\Actions;

use Service\AltinYildiz\Requests\CategoryClient;

class Category
{
    public function getCategoriesTree():array
    {
        $arr = [];
        $data = [];
        $arr[0] = 'Giyim';
        $arr[1] = 'Ayakkabı';
        $arr[2] = 'Aksesuar';
        $categories = new CategoryClient();

        $responses = $categories->getClothesCategories();

//        $data[$arr[0]] = [
//            'url' => 'giyim-c-2723',
//            'sub' => []
//        ];

//        foreach ($responses as $response){
//            $data[$arr[0]][$response] = $response;
//        }

        return $data;

    }
}
