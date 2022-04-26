<?php

namespace Service\Koton\Request;


trait ProductRequest
{
    public function getProductsFromHtml(string  $url): array
    {
        $query = $this->getFromHtml('.product-list-container .productGrid ', $url);

        if ($query->filter('.product-item')->count() == 0)
            return [];

        $arr[] = $query->filter('.product-item')->each(function ($node){

            $product['name'] =  $node->attr('data-name');

            $product['product_id'] = rand(1000000, 100000000);

            $product['product_code'] = $node->filter('span.addSize')->attr('data-code');

            $product['service_type'] = 4;

            $product['category_url'] = '';

            $product['product_url'] = $node->filter('a')->attr('href');

            $product['in_stock'] = 1;

            $product['internal_code'] = "kt_" . $product['product_code'];

            return $product;
        });

        return $arr[0];
    }

    public function getProductsCountFromHtml($url): ?string
    {
        $query = $this->getFromHtml('.product-list-title', $url);

        if ($query->filter('.plt-count')->count() == 0)
            return null;


        return $query->filter('.plt-count')->first()->text();
    }
}
