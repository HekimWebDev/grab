<?php

namespace Service\Avva\Request;

trait ProductRequest
{
    public function getProductsFromAPI($url, string $pageId, int $pageNumber): array
    {
        //get API from $url
        $this->getFromAPI($url);

        if ($this->response->getStatusCode() != 200)
            return [];

        $body = json_decode($this->response->getBody()->getContents(), true);

        if( empty(($body['products'])) )
            return [];

        foreach ($body['products'] as $item){

            $product['name'] = $item['name'];

            $product['product_id'] = $item['productId'];

            $product['product_code'] = $item['stockCode'];

            $product['service_type'] = 5;

            //Data too long for column 'category_url', so we can't to create full url
            $product['category_url'] = $pageId . '-' . $pageNumber;

            $product['product_url'] = $item['url'];

            $product['in_stock'] = 1;

            $product['internal_code'] = "av_" . $product['product_id'] . '_' . $product['product_code'];

            $arr[] = $product;
        }

        return $arr;
    }
}
