<?php

namespace Service\AltinYildiz\Requests;

use GuzzleHttp\Exception\GuzzleException;
use Service\AltinYildiz\Response;

trait ProductRequests
{
    /**
     * @throws GuzzleException
     */
    public function getProducts(array $categoriesPageList, int $pagezeSize = 5000): array
    {
        $allProducts = [];

        foreach ($categoriesPageList as $cat => $page_list) {

            $query = $this->getFromHTML('.listing-list .description', $page_list . "/?dropListingPageSize=$pagezeSize");

            $data[] = $query
                ->each(function ($node) {
                    $product['name'] = $node->filter('h2')->text();

                    $product['product_id'] = intval($node->filter('a')->attr('data-id'));

                    $product['product_code'] = $node->filter('a')->attr('data-code');

                    $product['service_type'] = 1;

                    $product['category_url'] = null;

                    $product['product_url'] = $node->filter('a')->attr('href');

                    $product['in_stock'] = 1;

                    $product['internal_code'] = "ay_" . $product['product_id'] . '_' . $product['product_code'];

//                    $product['created_at'] = null;
//
//                    $product['updated_at'] = null;

                    return $product;
                });

            $arrSize = count($data);

            foreach ($data[$arrSize - 1] as $key => $item){
                $data[$arrSize - 1][$key]['category_url'] = $page_list;
//                $data[$arrSize - 1][$key]['created_at'] = now();
//                $data[$arrSize - 1][$key]['updated_at'] = now();
            }

            $allProducts = array_merge($allProducts, $data[$arrSize - 1]);
        }
//        dd($allProducts);
        return $allProducts;
    }
}
