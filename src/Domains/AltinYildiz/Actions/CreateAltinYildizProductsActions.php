<?php

namespace Domains\AltinYildiz\Actions;

use Service\AltinYildiz\Requests\Products;

class CreateAltinYildizProductsActions
{
    public function createProductions()
    {
        $products = new Products();
        $products = $products->getProducts();
        dump($products);
    }
}
