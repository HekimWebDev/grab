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

                $product['name'] = $node->filter('.PName')->text();

                $product['product_id'] = $product_id;

                $product['product_code'] = 'ramsey';

                $product['service_type'] = 2;

                $product['category_url'] = null;

                $product['product_url'] = $node->filter('a')->attr('href');

                $product['in_stock'] = 1;

                $product['internal_code'] = "rs_$product_id" . "_$color_id";

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

                $product['internal_code'] = "rs_$product_id" . "_$color_id";

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

    public function getOnePrice($url): array
    {
        $query = $this->getFromHtml('.Prices', $url);

        $prices['original_price'] = $query->filter('.Price')->first()->text();
        $prices['sale_price'] = $query->filter('.Price')->last()->text();

        return $prices;
    }
}
