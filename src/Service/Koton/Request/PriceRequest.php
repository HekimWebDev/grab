<?php

namespace Service\Koton\Request;

trait PriceRequest
{
    public function getPriceFromHtml(string  $url): array
    {
        $query = $this->getFromHtml('.product-list-container .productGrid ', $url);

        $arr[] = $query->filter('.product-item')->each(function ($node){

            $q = $node->filter('div.price');


            if ( $q->filter('.firstPrice')->count() ){

                $product['original_price'] = $product['sale_price'] = $q->filter('.firstPrice')->text();

            } else {
                $product['original_price']  = $q->filter('.insteadPrice s')->text();
                $product['sale_price']      = $q->filter('.newPrice')->text();

            }

            $product['internal_code'] = "kt_" . $node->filter('span.addSize')->attr('data-code');

            return $product;
        });

        return $arr[0];
    }

    public function getOnePriceFromHtml(string $url): array
    {
        $query = $this->getFromHtml('price', $url);

        $prices['sale_price'] = $query->filter('.newPrice')->first()->text();

        $prices['original_price'] = $query->filter('.insteadPrice')->first()->text();

        return $prices;
    }
}
