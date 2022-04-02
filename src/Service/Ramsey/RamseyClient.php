<?php

namespace Service\Ramsey;

use Goutte\Client as GoutteClient;

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
                $product['product_id'] = intval($node->filter('a')->attr('data-product'));
                $product['in_stock'] = 1;
                $product['name'] = $node->filter('.PName')->text();
                $product['service_type'] = 2;
                $product['product_url'] = $node->filter('a')->attr('href');
                return $product;
            });

//        $data[1] = $query
//            ->each(function ($node) {
//                $product['original_price'] = $node->filter('a .PriceArea span.PPrice')->first()->text();
//                $product['sale_price'] = $node->filter('a .PriceArea span.PPrice')->last()->text();
//
//                return $product;
//            });

        return $data;
    }
}
