<?php

namespace Service\Mavi;


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
        return json_decode($this->response, true);
    }
}