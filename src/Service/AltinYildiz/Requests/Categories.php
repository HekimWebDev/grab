<?php

namespace Service\AltinYildiz\Requests;

use Service\AltinYildiz\AltinYildizClient;

class Categories extends AltinYildizClient
{
    public function getHtmlCategories(): string
    {

//        return html-den almak (hepde-de alyp durar yaly)
    }

    public function getJsonCategories(): bool|string
    {
        $data = [
//            '/orme-esnek-360-gomlek-c-2802',
            '/atayaka-gomlek-c-2824',
//            'desenli-gomlek-c-2741',
        ];
        return json_encode($data);
        // Json formatda gaytaryp bermek (productlary almak ucin)
    }


}
