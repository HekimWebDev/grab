<?php

namespace Service\AltinYildiz\Requests;

use Domains\AltinYildiz\Actions\Category;
use Service\AltinYildiz\Response;

trait ProductRequests
{
    // array of product ids
    public function getPrice(int $id) : array
    {
        $request = $this
                    ->guzzleClient()
                    ->request('GET', $this->baseURL . '/api/attributeselection/' . $id, ['http_errors' => false]);

        $result = (new Response($request))->body();

        // return clear response or prefilter then returnW
        return [
            'product_id'     => $result->id,
            // 'original_price' => $current_sale_price,
            // 'sale_price'     => $response_sale_price,
            'created_at'     => date('Y-m-d H-i-s'), //2022-01-30 17:03:05
            'updated_at'     => date('Y-m-d H-i-s'),
        ];
    }

    public function getProducts(array $categoriesPageList): array
    {
        $data = [];

        foreach ($categoriesPageList as $cat => $page_list) {
            $data[$cat] = $this
                ->getFromHTML('.listing-list .description', $page_list . '/?dropListingPageSize=5000')
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

                    $product['category_name'] = 'category_name';
                    $product['service_type'] = 1;

                    return $product;
                });
        }

        return $data;

    }
}
