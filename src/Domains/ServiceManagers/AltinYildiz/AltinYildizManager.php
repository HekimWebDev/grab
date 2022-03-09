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
    private $subs;
    private $startTime;

    public function __construct()
    {
        $this->service = new AltinYildizClient();
        $this->subs = [];
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
        for ($i=0; $i<3; $i++) {

            $responses = $this->service->getParentCategories($this->tree[$i]['url']);

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

    private function getSubs($url): ?array
    {
        $data = [];
        $response = $this->service->getSubCategories($url);

        if ($response['name'] == null){
            return null;
        }

        foreach ($response['name'] as $key => $value) {
            $data[] = [
                'name'  =>  $value,
                'url'   =>  $response['url'][$key],
                'sub'   =>  $this->getSubs($response['url'][$key])
            ];
        }

        return $data;
    }

    public function getSubCategories() : array
    {
        $path = storage_path('app/public/categories/') . 'AltinYildiz.json';
        $json = file_get_contents($path);
        $response = new Response($json);

        return $response->getSubs();
    }

//    public function getSubUrlsFromJson():array
//    {
//        $data = Response();
//        $data = response($this->response, true);
//        foreach ($data as $item){
//            $this->findSubsFromJson($item);
//        }
//        return $this->subs;
//    }
//
//    private function findSubsFromJson($data):void
//    {
//        if ($data['sub'] == null){
//            $this->subs[] = $data['url'];
//            return;
//        } else {
//            foreach ($data['sub'] as $item){
//                $this->findSubs($item);
//            }
//        }
//        return;
//    }

    public function createProductsEveryWeek()
    {
//        $categories = $this->getSubCategories();

        $categories = ['kapusonlu-sweatshirt-c-3066'];

        $products = $this->service->getProducts($categories);

        foreach ($products as $key => $product) {
            foreach ($product as $k => $item) {
                Product::firstOrCreate(['product_code' => $item['product_code']], $item);
                Price::firstOrCreate(['product_id' => $item['product_id']], $item);
            }
        }
    }

    /**
     */
    public function updatePrices()
    {
        $this->regTime(1);

        $products = Product::get();

        foreach($products as $product) {

            $priceResult = $this->service->getPrice($product->product_id);

            if (!empty($products)) {
               try {

                //    if ($product->sale !== ) {

                //    }
               } catch (\GuzzleHttp\ConnectException) {
                   //throw $th;
               }

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
