<?php

namespace Service\AltinYildiz\Requests;

trait CategoryRequests
{
    public function getProducts(array $categoriesPageList = array('/yeni-sezon/#o=3&g=2&ct=455&u=48&p=100')): array
    {
        $allProducts = [];
        foreach ($categoriesPageList as $cat => $page_list) {

            $query = $this->getFromHTML('.ProductList .Prd', $page_list);
            dd($query);
            $data[] = $query
                ->each(function ($node) {
                    $product['product_id'] = intval($node->filter('a')->attr('data-id'));
                    $product['in_stock'] = 1;
                    $product['name'] = $node->filter('h2')->text();
                    $product['product_code'] = $node->filter('a')->attr('data-code');
                    $product['service_type'] = 1;
                    $product['product_url'] = $node->filter('a')->attr('href');
                    $product['category_url'] = null;
                    $product['created_at'] = null;
                    $product['updated_at'] = null;

                    return $product;
                });

            $arrSize = count($data);

            foreach ($data[$arrSize - 1] as $key => $item){
                $data[$arrSize - 1][$key]['category_url'] = $page_list;
                $data[$arrSize - 1][$key]['created_at'] = now();-
                $data[$arrSize - 1][$key]['updated_at'] = now();
            }

            $allProducts = array_merge($allProducts, $data[$arrSize - 1]);
        }
//        dd($allProducts);
        return $allProducts;
    }
}
