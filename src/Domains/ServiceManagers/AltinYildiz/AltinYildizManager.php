<?php

namespace Domains\AltinYildiz\Actions;

use Domains\Products\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Service\AltinYildiz\AltinYildizClient;
use Service\AltinYildiz\Response;

class AltinYildizManager
{
    private $service;
    private $tree;
    private $categories;

    public function __construct()
    {
        $this->service = new AltinYildizClient();

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

    private function getSubs($url)
    {
        $data = [];
        $response = $this->categories->getSubCategories($url);

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

    public function createProductsEveryWeek()
    {
        $products = new Products();
        $products = $products->getProducts();

//        dd($products);
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
               } catch (\ $th) {
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
