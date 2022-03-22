<?php

namespace Service\AltinYildiz\Requests;

use GuzzleHttp\Exception\GuzzleException;
use Service\AltinYildiz\Response;

trait ProductRequests
{
    /**
     * @throws GuzzleException
     */
    public function getPrice(int $id)
    {
        $request = $this
            ->guzzleClient()
            ->request('GET', 'https://www.altinyildizclassics.com/api/attributeselection/' . $id, ['http_errors' => false]);

        $result = (new Response($request))->body();

        return $result['SalePrice'];
    }

    public function getProducts(array $categoriesPageList, int $pagezeSize = 5000): array
    {
        $allProducts = [];

        foreach ($categoriesPageList as $cat => $page_list) {

            $query = $this->getFromHTML('.listing-list .description', $page_list . "/?dropListingPageSize=$pagezeSize");

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
                $data[$arrSize - 1][$key]['created_at'] = now();
                $data[$arrSize - 1][$key]['updated_at'] = now();
            }

            $allProducts = array_merge($allProducts, $data[$arrSize - 1]);
        }
//        dd($allProducts);
        return $allProducts;
    }

    public function getProductsPrices(string $categoryUrl, int $pageSize = 5000): array
    {
        $data = $this
            ->getFromHTML('.listing-list .description', $categoryUrl . "/?dropListingPageSize=$pageSize")
            ->each(function ($node) {
                $product['product_id'] = intval($node->filter('a')->attr('data-id'));

                if ($node->filter('.data')->children()->count() < 2) {
                    $product['original_price'] = $node->filter('.data span')->text();
                    $product['sale_price'] = $node->filter('.data span')->text();
                } else {
                    $product['original_price'] = $node->filter('.data span')->eq(0)->text();
                    $product['sale_price'] = $node->filter('.data span')->eq(1)->text();
                }

                return $product;
            });

        return $data;
    }

    public function getOneProductPrices(string $categoryUrl, int $pagezeSize = 5000)
    {
        $data = $this
            ->getFromHTML('.listing-list .description', $categoryUrl . "/?dropListingPageSize=$pagezeSize")
            ->each(function ($node) {
                $product['product_id'] = intval($node->filter('a')->attr('data-id'));

                if ($node->filter('.data')->children()->count() < 2) {
                    $product['original_price'] = $node->filter('.data span')->text();
                    $product['sale_price'] = $node->filter('.data span')->text();
                } else {
                    $product['original_price'] = $node->filter('.data span')->eq(0)->text();
                    $product['sale_price'] = $node->filter('.data span')->eq(1)->text();
                }

                return $product;
            });

        return collect($data)->keyBy('product_id');
    }
}
