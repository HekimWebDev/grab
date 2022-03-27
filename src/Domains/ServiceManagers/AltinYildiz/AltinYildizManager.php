<?php

namespace Domains\ServiceManagers\AltinYildiz;

use Domains\Prices\Models\Price;
use Domains\Products\Models\Product;
use Service\AltinYildiz\AltinYildizClient;

class AltinYildizManager
{
    private AltinYildizClient $service;
    private array $tree;
    private $subUrls = [];

    public function __construct()
    {
        $this->service = new AltinYildizClient();
        $this->subs = [];
        $arr = [
            'Giyim' => '/giyim-c-2723',
            'Ayakkabı' => '/ayakkabi-c-2764',
            'Aksesuar' => '/aksesuar-c-2763',
        ];

        foreach ($arr as $key => $value) {
            $this->tree[] = [
                'name' => $key,
                'url' => $value,
                'sub' => []
            ];
        }
    }

    private function findSubs($data)
    {
        if ($data['sub'] == null){
            $this->subUrls[] = $data['url'];
            return null;
        } else {
            foreach ($data['sub'] as $item){
                $this->findSubs($item);
            }
        }
        return null;
    }

    private function getSubsFromHtml($url): ?array
    {
        $data = [];
        $response = $this->service->getSubCategories($url);

        if ($response['name'] == null) {
            return null;
        }

        foreach ($response['name'] as $key => $value) {
            $data[] = [
                'name'  =>  $value,
                'url'   =>  $response['url'][$key],
                'sub'   =>  $this->getSubsFromHtml($response['url'][$key])

            ];
        }

        return $data;
    }

    public function grabCategoriesTreeFromHtml():array
    {
        for ($i = 0; $i < 3; $i++) {

            $responses = $this->service->getParentCategories($this->tree[$i]['url']);

            $data = [];
            foreach ($responses['name'] as $key => $response) {
                $data[] = [
                    'name' => $response,
                    'url' => $responses['url'][$key],
                    'sub' => $this->getSubsFromHtml($responses['url'][$key])
                ];
            }
            $this->tree[$i]['sub'] = $data;
        }

        return $this->tree;
    }

    public function getSubCategoriesForGrab() : array
    {
        $path = storage_path('app/public/categories/') . 'AltinYildiz.json';

        $data = json_decode(file_get_contents($path), true);

        foreach ($data as $item){
            $this->findSubs($item);
        }

        return $this->subUrls;
    }

    public function getProducts(array $categories)
    {
        return $this->service->getProducts($categories);
    }

    public function getPrices(string $category)
    {
        return $this->service->getProductsPrices($category);
    }

    public function checkPrice($category_url)
    {
        return $this->service->getOneProductPrices($category_url);
    }
}
