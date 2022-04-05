<?php

namespace Service\Ramsey;

use Goutte\Client as GoutteClient;
use Illuminate\Support\Str;

class RamseyClient
{
    protected string $url;

    protected mixed $baseUrl;

    private $response;

    public function __construct()
    {
        $this->baseUrl = config('grabconfig.Ramsey.base_url');
    }

    public function goutteClient(): GoutteClient
    {

        return new GoutteClient();
    }

    public function getFromHtml($tag, string $url = '')
    {
        $url = $this->baseUrl . $url;

        $this->response = $this->goutteClient()->request('GET', $url);

        return $this->response->filter($tag);
    }

    public function getProducts(string $page_list): array
    {
        $query = $this->getFromHtml('.ProductList .Prd', $page_list);

        $data[] = $query
            ->each(function ($node) {

                $color_id = intval($node->filter('button')->attr('data-colorid'));

                $product_id = intval($node->filter('button')->attr('data-productid'));

                $idMerge = $product_id * pow(10, strlen( (string)$color_id )) + $color_id;

                $product['name'] = $node->filter('.PName')->text();

                $product['product_id'] = $idMerge;

                $product['product_code'] = '#ramsey#' . Str::random(20);

                $product['service_type'] = 2;

                $product['category_url'] = null;

                $product['product_url'] = $node->filter('a')->attr('href');

                $product['in_stock'] = 1;

                return $product;
            });

        return $data;
    }

    public function getPrices(string $page_list): array
    {
        $query = $this->getFromHtml('.ProductList .Prd', $page_list);

        $data[] = $query
            ->each(function ($node) {

                $color_id = intval($node->filter('button')->attr('data-colorid'));

                $product_id = intval($node->filter('button')->attr('data-productid'));

                $idMerge = $product_id * pow(10, strlen( (string)$color_id )) + $color_id;

                $product['product_id'] = $idMerge;

                $product['original_price'] = $node->filter('a .PriceArea span.PPrice')->first()->text();

                $product['sale_price'] = $node->filter('a .PriceArea span.PPrice')->last()->text();

                return $product;
            });

        return $data;
    }

    public function getProductCode ($url): string
    {
        $query = $this->getFromHtml('.panel-body p', $url);

        $product_code = $query->first()->text();

        return $product_code;
    }
}
