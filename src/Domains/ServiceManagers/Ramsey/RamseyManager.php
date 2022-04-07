<?php
/**
 * Created by PhpStorm.
 * User: Ysmayyl
 * Date: 30.03.2022
 * Time: 13:50
 */

namespace Domains\ServiceManagers\Ramsey;

use phpDocumentor\Reflection\Types\Collection;
use Service\Ramsey\RamseyClient;

class RamseyManager
{
    private RamseyClient $service;
//    private $service;

    public function __construct()
    {
        $this->service = new RamseyClient();
    }

    /**
     * @return array
     */
    public function getUrlsForGrab()
    {
//        $path = storage_path('app/public/categories/') . 'Ramsey.json';
        $path = base_path('public/url/') . 'Ramsey.json';

        $response = json_decode(file_get_contents($path), true);

        $data = [];

        foreach ($response as $item){
            $data[] = $item["url"];
        }

        return $data;
    }

    public function getProducts(string $url = null): array
    {
        $data = $this->service->getProducts($url);

        $arrSize = count($data[0]);

        for ($key = 0; $key < $arrSize; $key++){
            $data[0][$key]['category_url'] = $url;
            $data[0][$key]['created_at'] = now();
            $data[0][$key]['updated_at'] = now();
        }

        return $data[0];
    }

    public function getPrices(string $url = null)
    {
        $data = $this->service->getPrices($url);

        return $data[0];
    }

    public function getProductCode(string $url)
    {
        $code = $this->service->getProductCode($url);

        return substr($code, strpos($code, ': ') + 2);
    }

}
