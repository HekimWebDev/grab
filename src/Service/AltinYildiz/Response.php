<?php

namespace Service\AltinYildiz;

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
        return json_decode(
            $this->response->getBody(), true, 512, JSON_BIGINT_AS_STRING
        );
    }

    public function getRaw()
    {
        return $this->response;
    }

    public function getArray()
    {
        return json_decode($this->response, true);
    }

    private function toFloat($num): float
    {
        $dotPos = strrpos($num, '.');
        $commaPos = strrpos($num, ',');
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
            ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $num));
        }

        return floatval(
            preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
            preg_replace("/[^0-9]/", "", substr($num, $sep + 1, strlen($num)))
        );
    }
}
