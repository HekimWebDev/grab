<?php
/**
 * Created by PhpStorm.
 * User: Ysmayyl
 * Date: 30.03.2022
 * Time: 13:50
 */

namespace Domains\ServiceManagers\Ramsey;

use Service\Ramsey\RamseyClient;

class RamseyManager
{
    private RamseyClient $service;
//    private $service;

    public function __construct()
    {
        $this->service = new RamseyClient();
    }

    public function getUrlsForGrab()
    {
        $path = storage_path('app/public/categories/') . 'Ramsey.json';

        $response = json_decode(file_get_contents($path), true);

        $data = [];

        foreach ($response as $item){
            $data[] = $item["url"];
        }

        return $data;
    }

    public function getProducts(string $url = null): array
    {
        $url = '/tr/p/Home/GetProductList?o=3&g=2&ct=455&u=2000';

        $data = $this->service->getProducts($url);

        $arrSize = count($data);

        for($key = 0; $key < $arrSize; $key++){
            $data[0][$key]['category_url'] = $url;
            $data[0][$key]['created_at'] = now();
            $data[0][$key]['updated_at'] = now();

//            $data[1][$key]['created_at'] = now();
//            $data[1][$key]['updated_at'] = now();
        }

        return $data;
    }

}
