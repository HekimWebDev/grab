<?php

namespace Domains\ServiceManagers\AltinYildiz;

use Domains\Prices\Models\Price;
use Domains\Products\Models\Product;
use GuzzleHttp\Exception\GuzzleException;
use Service\AltinYildiz\AltinYildizClient;
use Service\AltinYildiz\Response;

class AltinYildizManager
{
    private AltinYildizClient $service;
    private array $tree;
    private $categories;
    private $subUrls = [];
    private $startTime;

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

    public function grabCategoriesTreeFromHtml():array
//    public function grabCategoriesTree(): array
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

    public function getSubCategoriesForGrab() : array
//    public function getSubCategories(): array
    {
        $path = storage_path('app/public/categories/') . 'AltinYildiz.json';
        $response = new Response(file_get_contents($path));
        $data = $response->getArray();
        foreach ($data as $item){
            $this->findSubs($item);
        }
        return $this->subUrls;
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

    public function createProducts()
    {
        $categories = $this->getSubCategoriesForGrab();
        dd($categories);
//        $categories = ['kapusonlu-sweatshirt-c-3066'];

        $products = $this->service->getProducts($categories);

        foreach ($products as $key => $product) {
            foreach ($product as $k => $item) {
                Product::firstOrCreate(['product_code' => $item['product_code']], $item);
                Price::firstOrCreate(['product_id' => $item['product_id']], $item);
            }
        }
    }



    /**
     * @throws GuzzleException
     */
    public function updatePrices($id)
    {
        $this->regTime(1);

        if ($id)
            $products[] = Product::find($id);
        else
            $products = Product::limit(3)->get();
//        dd($products);
        foreach ($products as $product) {
            $responsePriceResult = $this->service->getPrice($product->product_id);
            if ($responsePriceResult != $product->price->sale_price) {
                $data = [
                    'product_id' => $product->product_id,
                    'original_price' => max($responsePriceResult, $product->price->original_price),
                    'sale_price' => $responsePriceResult,
                    'created_at' => now(), //2022-01-30 17:03:05
                    'updated_at' => now(),
                ];

                Product::create($data);
            }

        }
    }

    private function regTime($status = null)
    {
        if ($status) {
            if (empty($this->startTime)) $this->startTime = new \DateTime('now');
        } else {
            $endTime = new \DateTime('now');
            $interval = $this->startTime->diff($endTime);
            $this->startTime = '';
            return dump($interval->format('%i минута, %S секунд, %f  микросекунд'));
        }
        return null;
    }

}
