<?php

namespace Service\AltinYildiz\Requests;

use Domains\AltinYildiz\Actions\Category;
use GuzzleHttp\Exception\GuzzleException;
use Service\AltinYildiz\Response;

trait ProductRequests
{
    // array of product ids
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
        $data = [];

        foreach ($categoriesPageList as $cat => $page_list) {
//            $data[$cat] = $this
            $data[$page_list] = $this
                ->getFromHTML('.listing-list .description', $page_list . "/?dropListingPageSize=$pagezeSize")
                ->each(function ($node) {

                    $product['product_id'] = intval($node->filter('a')->attr('data-id'));

                    $product['name'] = $node->filter('h2')->text();

                    $product['product_url'] = $node->filter('a')->attr('href');
                    $product['product_code'] = $node->filter('a')->attr('data-code');

                    if ($node->filter('.data')->children()->count() < 2) {
                        $product['original_price'] = $node->filter('.data span')->text();
                        $product['sale_price'] = $node->filter('.data span')->text();
                    } else {
                        $product['original_price'] = $node->filter('.data span')->eq(0)->text();
                        $product['sale_price'] = $node->filter('.data span')->eq(1)->text();
                    }

                    $product['category_url'] = 'category_name';
                    $product['service_type'] = 1;

                    return $product;
                });

            if ($cat == 1)
                return $data;
        }
        return $data;

    }

    public function getProductsPrices(string $categoryUrl, int $pagezeSize = 5000): array
    {
        $data = $this
            ->getFromHTML('.listing-list .description', $categoryUrl . "/?dropListingPageSize=$pagezeSize")
            ->each(function ($node) {

                $product['product_id'] = intval($node->filter('a')->attr('data-id'));
//                $product['product_code'] = $node->filter('a')->attr('data-code');

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
}
