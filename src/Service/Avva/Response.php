<?php

namespace Service\Avva;

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
}
