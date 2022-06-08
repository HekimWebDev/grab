<?php

namespace Service\Avva;

class Response
{
    private $response;


    public function __construct($response)
    {
        $this->response = $response;
    }

    public function getJson($response = null)
    {
        if (empty($response))
            $response = $this->response;

        return json_encode($response);
    }
}
