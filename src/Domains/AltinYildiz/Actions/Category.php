<?php

namespace Domains\AltinYildiz\Actions;

use Service\AltinYildiz\Requests\CategoryClient;

class Category
{
    private $tree;
    private $categories;

    public function __construct()
    {
        $this->categories = new CategoryClient();
        $arr = [
            'Giyim' =>  'giyim-c-2723',
            'Ayakkabı' => 'ayakkabi-c-2764',
            'Aksesuar' => 'aksesuar-c-2763',
        ];

        foreach ($arr as $key => $value){
            $this->tree[] = [
                'name'  => $key,
                'url'   => $value,
                'sub'   => []
            ];
        }
    }

    public function getCategoriesTree():array
    {
        $data = [];

        for ($i=0; $i<1; $i++) {
            $responses = $this->categories->getCategories($this->tree[$i]['url']);

            foreach ($responses['name'] as $key => $response) {
                $data[] = [
                    'name' => $response,
                    'url' => $responses['url'][$key],
                    'sub' => $this->getSubs($responses['url'][$key])
                ];
            }

            $this->tree[$i]['sub'] = $data;
        }

        return $this->tree;
    }

    private function getSubs($url)
    {
        $data = [];
        $response = $this->categories->getSubCategories($url);

        if ($response['name'] == null){
            return null;
        }

        foreach ($response['name'] as $key => $value) {
//            dump($value);
            $data[] = [
                'name'  =>  $value,
                'url'   =>  $response['url'][$key],
                'sub'   =>  $this->getSubs($response['url'][$key])
            ];
        }

        return $data;
    }
}
