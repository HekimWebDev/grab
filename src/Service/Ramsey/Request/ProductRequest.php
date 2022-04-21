<?php
/**
 * Created by PhpStorm.
 * User: Windows
 * Date: 21.04.2022
 * Time: 23:14
 */

namespace Service\Ramsey\Request;


trait ProductRequest
{
    public function getProducts(string $page_list): array
    {
        $query = $this->getFromHtml('.ProductList .Prd', $page_list);

        $data[] = $query
            ->each(function ($node) {

                $color_id = intval($node->filter('button')->attr('data-colorid'));

                $product_id = intval($node->filter('button')->attr('data-productid'));

                $product['name'] = $node->filter('.PName')->text();

                $product['product_id'] = $product_id;

                $product['product_code'] = 'ramsey';

                $product['service_type'] = 2;

                $product['category_url'] = null;

                $product['product_url'] = $node->filter('a')->attr('href');

                $product['in_stock'] = 1;

                $product['internal_code'] = "rs_$product_id" . "_$color_id";

                return $product;
            });

        return $data;
    }

    public function getProductCode ($url): string
    {
        $query = $this->getFromHtml('.panel-body p', $url);

        $product_code = $query->first()->text();

        return $product_code;
    }
}