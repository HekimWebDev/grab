<?php
/**
 * Created by PhpStorm.
 * User: Ysmayyl
 * Date: 30.03.2022
 * Time: 13:08
 */

namespace Service\Ramsey;


class Response
{

    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function getJson()
    {
        return json_encode($this->response);
    }
}
