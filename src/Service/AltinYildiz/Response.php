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

    public function getJson():json
    {
        return json_encode($this->response);
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
//            $this->subs[] = 'https://www.altinyildizclassics.com' . $data['url'];
            $this->subs[] = $data['url'];
            return;
        } else {
            foreach ($data['sub'] as $item){
                $this->findSubs($item);
            }
        }
        return;
    }
}
