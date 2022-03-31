<?php
/**
 * Created by PhpStorm.
 * User: Ysmayyl
 * Date: 30.03.2022
 * Time: 13:50
 */

namespace Domains\ServiceManagers\Ramsey;

class RamseyManager
{
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

    public function getProducts()
    {

    }

}
