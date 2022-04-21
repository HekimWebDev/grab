<?php

namespace Service\Ramsey\Request;


trait PriceRequest
{
    public function getPrices(string $page_list): array
    {
        $query = $this->getFromHtml('.ProductList .Prd', $page_list);

        $data[] = $query
            ->each(function ($node) {

                $color_id = intval($node->filter('button')->attr('data-colorid'));

                $product_id = intval($node->filter('button')->attr('data-productid'));

                $product['internal_code'] = "rs_$product_id" . "_$color_id";

                $product['original_price'] = $node->filter('a .PriceArea span.PPrice')->first()->text();

                $product['sale_price'] = $node->filter('a .PriceArea span.PPrice')->last()->text();

                return $product;
            });

        return $data;
    }

    public function getOnePrice($url): array
    {
        $query = $this->getFromHtml('.Prices', $url);

        $prices['original_price'] = $query->filter('.Price')->first()->text();
        $prices['sale_price'] = $query->filter('.Price')->last()->text();

        return $prices;
    }
}