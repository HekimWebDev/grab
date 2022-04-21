<?php

namespace Service\Mavi\Request;


trait PriceRequest
{
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