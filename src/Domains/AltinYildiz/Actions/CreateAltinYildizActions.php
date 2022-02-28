<?php

namespace Domains\AltinYildiz\Actions;

use Domains\Prices\Models\Price;
use Domains\Products\Models\Product;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use Service\AltinYildiz\Requests\Products;

class CreateAltinYildizActions
{
    public function createProductsEveryWeek()
    {
        $products = new Products();
        $products = $products->getProducts();

//        dd($products);
        foreach ($products as $key => $product) {
//            foreach ($product as $k => $item) {
//                Product::firstOrCreate(['product_id' => $item['product_id']], $item);
//                Price::firstOrCreate(['product_id' => $item['product_id']], $item);
//            }
            if (Str::contains($key, 'prod')) {
                Product::upsert($product, 'product_id');
            }

            if (Str::contains($key, 'price')) {
                Price::upsert($product, 'id');
            }
        }
    }

    /**
     * @throws GuzzleException
     */
    public function checkDailyPrices()
    {
        $product = new Products();
        $products = $product->checkPrices();

//        dump($products);
        if (!empty($products)) {
            foreach ($products as $index => $product) {
                Price::create($product);
            }
        }
    }

}
