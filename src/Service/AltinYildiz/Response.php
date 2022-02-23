<?php

namespace Service\AltinYildiz;

class Response
{
    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return json_encode($this->response);
    }
}
