<?php

namespace Domains\AltinYildiz\Actions;

use Domains\Products\Models\Product;
use Service\AltinYildiz\Requests\Products;

class CreateAltinYildizActions
{
    public function createProductsEveryWeek()
    {
        $products = new Products();
        $products = $products->getProducts();

        foreach ($products as $product) {
//            dump($product);
            Product::upsert($product, ['product_code', 'product_url']);
        }
    }

}
