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
}
