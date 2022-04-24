<?php
/**
 * Created by PhpStorm.
 * User: Windows
 * Date: 21.04.2022
 * Time: 23:06
 */

namespace Service\Koton;


class Response
{
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function getJson()
    {
        return json_encode($this->response);
    }

    public function body()
    {
        return json_decode($this->response);
    }

}
