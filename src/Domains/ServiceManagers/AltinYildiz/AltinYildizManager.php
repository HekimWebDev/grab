<?php

namespace Domains\ServiceManagers\AltinYildiz;

use Domains\Prices\Models\Price;
use Domains\Products\Models\Product;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Boolean;
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
        $productUpSert = [];
        $categories = $this->getSubCategoriesForGrab();
//        $categories = ['kapusonlu-sweatshirt-c-3066'];

        $productsArr = $this->service->getProducts($categories);

        //        in_stock off
        $change = Product::where('in_stock', 1)
                            ->update(['in_stock' => 0]);

        $productFillables = [
            'name',
            'product_id',
            'product_code',
            'service_type',
            'category_url',
            'product_url',
            'in_stock'
        ];
        Product::upsert($productsArr, ['product_id'], $productFillables);

//        Price::firstOrCreate(['product_id' => $product['product_id']], $product);
    }

    public function updatePrice()
    {
        $data = [];
        $money = new \App\Casts\Money();

        $products = Product::where('service_type', 1)
            ->where('in_stock', 1)
            ->get()
            ->groupBy('category_url')
            ->map(function ($q){
                return $q->keyBy('product_id');
            });
//        dd($products);
        $i = 0;
        foreach ($products as $categoryUrl => $product){
            $i++;
            dump("$i) $categoryUrl");
            $pricesFromHtml = $this->service->getProductsPrices($categoryUrl);

            foreach ($pricesFromHtml as $newPrices){

                if( isset($product[$newPrices['product_id']]) ){
                    $oldPrices = $product[$newPrices['product_id']]->price;
                    $nOriginPrice = $money->set('', 'k', $newPrices['original_price'], [])['k'];
                    $nSalePrice = $money->set('', 'k', $newPrices['sale_price'], [])['k'];

                    if (empty($oldPrices) || !($oldPrices->original_price == $nOriginPrice && $oldPrices->sale_price == $nSalePrice) ){
                        $data[] = [
                            'product_id' => $newPrices['product_id'],
                            'original_price' => $nOriginPrice,
                            'sale_price' => $nSalePrice,
                            'created_at' => now(), //2022-01-30 17:03:05
                            'updated_at' => now(),
                        ];
                    }

                    $product[$newPrices['product_id']]->touch();
                }
            }
        }

        Price::insert($data);
    }

    public function checkPrice(Product $product):Bool
    {
        $respone = false;
        $money = new \App\Casts\Money();

        $pricesFromHtml = $this->service->getOneProductPrices($product->category_url);

//        dd($pricesFromHtml[$product->product_id]);
        if( isset($pricesFromHtml[$product->product_id]) ) {

            $newPrices = $pricesFromHtml[$product->product_id];
            $oldPrices = $product->price;
            $nOriginPrice = $money->set('', 'k', $newPrices['original_price'], [])['k'];
            $nSalePrice = $money->set('', 'k', $newPrices['sale_price'], [])['k'];

            if (empty($oldPrices) || !($oldPrices->original_price == $nOriginPrice && $oldPrices->sale_price == $nSalePrice)) {
                Price::create([
                    'product_id' => $product->product_id,
                    'original_price' => $newPrices['original_price'],
                    'sale_price' => $newPrices['sale_price'],
                ]);
                $respone = true;
            }
            $product->touch();
        }
        return $respone;
    }
}
