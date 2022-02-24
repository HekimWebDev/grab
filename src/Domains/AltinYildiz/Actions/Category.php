<?php

namespace Domains\AltinYildiz\Actions;

use Service\AltinYildiz\Requests\CategoryClient;
use Service\AltinYildiz\Response;

class Category
{
    private $tree;
    private $categories;

    public function __construct()
    {
        $this->categories = new CategoryClient();
        $arr = [
            'Giyim' =>  '/giyim-c-2723',
            'Ayakkabı' => '/ayakkabi-c-2764',
            'Aksesuar' => '/aksesuar-c-2763',
        ];

        foreach ($arr as $key => $value){
            $this->tree[] = [
                'name'  => $key,
                'url'   => $value,
                'sub'   => []
            ];
        }
    }

    public function grabCategoriesTree():array
    {
//        $data = [];

        for ($i=0; $i<3; $i++) {
            $responses = $this->categories->getCategories($this->tree[$i]['url']);
            $data = [];
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
//        dd($response);
        foreach ($response['name'] as $key => $value) {
            $data[] = [
                'name'  =>  $value,
                'url'   =>  $response['url'][$key],
                'sub'   =>  $this->getSubs($response['url'][$key])
            ];
        }

        return $data;
    }

    public function getSubCategories():array
    {
        $path = storage_path('app/public/categories/') . 'AltinYildiz.json';
        $json = file_get_contents($path);
        $response = new Response($json);

        return $response->getSubs();
    }
}
