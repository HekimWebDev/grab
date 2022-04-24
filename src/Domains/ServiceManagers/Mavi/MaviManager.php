<?php

namespace Domains\ServiceManagers\Mavi;

use Service\Mavi\MaviClient;
use Service\Mavi\Response;

class MaviManager
{
    protected $cilent;

    public function __construct()
    {
        $this->client = new MaviClient();
    }

    public function getCategories(): array
    {
        $urls = [];
        $categories = $this->client->getCategoriesFromHtml();

        foreach ($categories as $items){

            foreach ($items as $name => $item){

                if ($name == 'url'){
                    $urlOfParent = $item;
                    continue;
                }

                foreach ($item as $key => $value){

                    $position = strpos($value, '?q=:relevance');

                    if ($position > 0){

                        $urls[] = $urlOfParent . '/results' . substr($value, $position) . '&page=';

                        continue;
                    }

                    $position = strpos($value, 'aksesuar');

                    if ($position > 0){

                        $urls[] = $urlOfParent . "/results?q=:relevance:categoryValue:Aksesuar:subCategoryValue:$key&page=";

                        continue;
                    }

                    $urls[] = $urlOfParent . "/results?q=:relevance:categoryValue:$key&page=";
                }
            }
        }

        return $urls;
    }

    public function getUrl(): array
    {
        $path = storage_path('app/public/categories/') . 'Mavi.json';

        $response = new Response(file_get_contents($path));

        $urls = $response->body();

        return $urls;
    }

    public function getProducts(string $url)
    {
        $response = $this->client->getProductsFromAPI($url);

        return $response;
    }

    public function getPrices(string $url)
    {
        $response = $this->client->getPricesFromAPI($url);

        return $response;
    }

    public function checkPriceFromHtml($url)
    {
        $arr = $this->client->getOnePrice($url);

        return $arr;
    }
}
