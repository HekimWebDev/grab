<?php

namespace Service\Mavi\Request;


trait ProductRequest
{
    public function getProductsFromAPI($url): array
    {
        $this->getFromAPI($url);

        if ($this->response->getStatusCode() != 200)
            return [];

        $body = json_decode($this->response->getBody()->getContents(), true);

        if( empty(($body['results'])) )
            return [];

        foreach ($body['results'] as $item){

            $id = rand(1000000, 100000000);

            $product['name'] = $item['name'];

            $product['product_id'] = $id;

            $product['product_code'] = $item['code'];

            $product['service_type'] = 3;

            $product['category_url'] = $url;

            $product['product_url'] = $item['url'];

            $product['in_stock'] = 1;

            $product['internal_code'] = "mv_" .$item['code'];

            $arr[] = $product;
        }

        return $arr;
    }

    public function getPricesFromAPI($url): array
    {
        $this->getFromAPI($url);

        if ($this->response->getStatusCode() != 200)
            return [];

        $body = json_decode($this->response->getBody()->getContents(), true);

        if( empty(($body['results'])) )
            return [];

        foreach ($body['results'] as $key => $item){

            $product['original_price'] = $item['price']['value'];

            if ($item['bestPrice'] == null)
                $product['sale_price'] = $product['original_price'];
            else
                $product['sale_price'] = $item['bestPrice']['value'];

            $product['internal_code'] = "mv_" .$item['code'];

            $arr[] = $product;
        }

        return $arr;
    }
}