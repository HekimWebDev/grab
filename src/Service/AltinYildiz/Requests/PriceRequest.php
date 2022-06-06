<?php

namespace Service\AltinYildiz\Requests;


trait PriceRequest
{
    public function getPrice(int $id)
    {
        $request = $this
            ->guzzleClient()
            ->request('GET', 'https://www.altinyildizclassics.com/api/attributeselection/' . $id, ['http_errors' => false]);

        $result = (new Response($request))->body();

        return $result['SalePrice'];
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

    public function getProductsPrices(string $categoryUrl, int $pageSize = 5000): array
    {
        $data = $this
            ->getFromHTML('.listing-list .description', $categoryUrl . "/?dropListingPageSize=$pageSize")
            ->each(function ($node) {
                $product['product_id'] = intval($node->filter('a')->attr('data-id'));

                $product['product_code'] = $node->filter('a')->attr('data-code');

                $product['internal_code'] = "ay_" . $product['product_id'] . '_' . $product['product_code'];

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

    public function getOnePrice($url): array
    {
        $query = $this->getFromHTML('.description', $url);

        $prices['original_price'] = $query->filter('.data .price')->first()->text();
        $prices['sale_price'] = $query->filter('.data .price')->last()->text();

        return $prices;
    }
}
