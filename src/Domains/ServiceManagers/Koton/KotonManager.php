<?php

namespace Domains\ServiceManagers\Koton;

use Service\Koton\KotonClient;
use Service\Koton\Response;

class KotonManager
{
    protected $client;

    public function __construct()
    {
        $this->client = new KotonClient();
    }

    public function getCategories()
    {
        $arr = $this->client->getCategoriesFromHtml();

        foreach($arr[0] as $item)
            foreach ($item as $value) {
                $url[] = $value;
            }

        return $url;
    }

    public function getUrl()
    {
        $path = storage_path('app/public/categories/Koton.json');

        $response = new Response(file_get_contents($path));

        $url = $response->body();

        return $url;
    }

    public function getProducts(string $url)
    {
        $products = $this->client->getProductsFromHtml($url);

        for ($i = 0; $i < count($products); $i++){
            $products[$i]['category_url'] = $url;
        }

        return $products;
    }

    public function getPrices(string $url)
    {
        $arr = $this->client->getPriceFromHtml($url);

        return $arr;
    }

    public function getProductCount($url): int
    {
        $count = $this->client->getProductsCountFromHtml($url);

        if (empty($count))
            return -1;

        return getProductsCount($count);
    }
}
