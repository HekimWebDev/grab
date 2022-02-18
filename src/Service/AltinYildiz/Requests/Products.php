<?php

namespace Service\AltinYildiz\Requests;

class Products extends Categories
{
    protected array $result = [];

    public function getProducts(): array
    {
        $data = $this->getJsonCategories();
        $data = json_decode($data);
        $res = [];
        foreach ($data as $k => $datum) {
            $url = $this->url . '/' . $datum;
            $res = $this->getResponse('.thintitle a', 'desenli-gomlek-c-2741/?dropListingPageSize=5000')->each(function ($node) {
                return $node->getUri();
            });
        }
        return $res;
    }
}
