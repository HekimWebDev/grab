<?php

namespace Service\AltinYildiz\Requests;

class Products extends Categories
{
    private string $page_size = '/?dropListingPageSize=5000';

    public function getProducts(): array
    {
        $data = $this->getJsonCategories();
        $categories = json_decode($data);
        $href = [];
        foreach ($categories as $k => $category) {
            $url = $category . $this->page_size;
//            $href = $this->getResponse('.thintitle a', $url)->each(function ($node) {
//                return $node->attr('href');
//            });
            $href[$k] = $this->client->request('GET', $this->url . $url)
                ->filter('.thintitle a')->each(function ($node) {
                    return $node->attr('href');
                });
        }
//        return $href;
        return $this->getProductPage($href);

    }

    private function getProductPage($href): array
    {
        $data = [];
        foreach ($href as $cat => $url) { // 2 kategoriyalar
//            $data[$cat] = [];
            foreach ($url as $prod => $uri) { // 1 productalar
                $data[$cat.'cat'][$prod.'prod'] = \Arr::collapse($data[$cat.'cat'][$prod.'prod'] = $this->getResponse('.product-details', $uri)
                    ->each(function ($node) {
                        $array['url'] = $node->getUri();
                        $array['name'] = $node->filter('.thintitle span')->eq(1)->text();

                        if ($node->filter('.data')->children()->count() == 1) {
                            $array['original_price'] = $node->filter('.data span')->eq(0)->text();
                        } else {
                            $array['original_price'] = $node->filter('.data span')->eq(0)->text();
                            $array['sale_price'] = $node->filter('.data span')->eq(1)->text();
                        }

                        $array['code_product'] = $node->filter('.description li')->text();
                        $array['category'] = 'category_name';

                        return $array;
                    }));
            }
        }
        return $data;
//        return \Arr::collapse($data);
    }
}
