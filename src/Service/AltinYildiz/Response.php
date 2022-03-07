<?php

namespace Service\AltinYildiz;

class Response
{
    private $response;
    private $subs;

    public function __construct($response)
    {
        $this->response = $response;
        $this->subs = [];
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

    public function getSubs():array
    {
        $data = json_decode($this->response, true);
        foreach ($data as $item){
            $this->findSubs($item);
        }
        return $this->subs;
    }

    private function findSubs($data):void
    {
        if ($data['sub'] == null){
            $this->subs[] = $data['url'];
            return;
        } else {
            foreach ($data['sub'] as $item){
                $this->findSubs($item);
            }
        }
        return;
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
