<?php

namespace Service\AltinYildiz\Requests;

use JetBrains\PhpStorm\ArrayShape;

class Products extends Categories
{
    private string $page_size = '/?dropListingPageSize=5000';

    public function getProducts(): array
    {
        $data = $this->getJsonCategories();
        $categories = json_decode($data);
        return $this->getProductPage($categories);

    }

    private function getProductPage($categories): array
    {
        $data = [];
        foreach ($categories as $cat => $page_list) {
            dump($this->url.$page_list);
            $data[$cat] = $this->getResponse('.listing-list .description', $page_list)->each(function ($node){
                $product['name'] = $node->filter('h2')->text();
                $product['product_url'] = $node->filter('a')->attr('href');
                $product['product_code'] = $node->filter('a')->attr('data-code');

                if ($node->filter('.data')->children()->count() < 2) {
                    $product['original_price'] = $this->replaceStringToFloat($node->filter('.data span')->text());
                    $product['sale_price'] = 00.0;
                    $product['discount'] = 00.0;
                } else {
                    $product['original_price'] = $this->replaceStringToFloat($node->filter('.data span')->eq(0)->text());
                    $product['sale_price'] = $this->replaceStringToFloat($node->filter('.data span')->eq(1)->text());
                    $product['discount'] = $product['original_price'] - $product['sale_price'];
                }

                $product['category_name'] = 'category_name';
                $product['brand'] = 'AltinYildiz classics';
                $product['created_at'] = date('Y-m-d h-i-s'); //2022-01-30 17:03:05
                $product['updated_at'] = date('Y-m-d h-i-s');


                return $product;
            });
        }
        return $data;

    }

    private function getBetweenString($code): string
    {
        $code = \Str::after($code, 'Ürün Kodu:');
        $code = \Str::before($code, ',');
        return \Str::before($code, '.');
    }

    private function replaceStringToFloat($code): float
    {
        $code = \Str::before($code, 'TL');
        return floatval(str_replace(',', '.', $code));
    }

    #[ArrayShape(['0cat' => "\string[][]", '1cat' => "\string[][]"])] public function tempData(): array
    {
        return [
            '0cat' => [
                '0prod' => [
                    "name" => "360 DERECE HER YÖNE ESNEYEN DÜĞMELİ YAKA ÖRME TAİLORED SLİM FİT GÖMLEK",
                    "product_url" => "https://www.altinyildizclassics.com//p_360-derece-her-yone-esneyen-dugmeli-yaka-orme-tailored-slim-fit-gomlek_5291969_2802",
                    "original_price" => 299.99,
                    "sale_price" => 169.99,
                    "discount" => 130.0,
                    "code_product" => " 4A2021200376LAC",
                    "category" => "category_name",
                ]
            ],
            '1cat' => [
                "0prod" => [
                    "name" => "BEYAZ DAMATLIK ATA YAKA TAİLORED SLİM FİT GÖMLEK",
                    "product_url" => "https://www.altinyildizclassics.com//p_beyaz-damatlik-ata-yaka-tailored-slim-fit-gomlek_3587354_2824",
                    "original_price" => 899.99,
                    "sale_price" => 499.99,
                    "discount" => 400.0,
                    "code_product" => " 4A2020200208BYZ",
                    "category" => "category_name",
                ],
                "1prod" => [
                    "name" => "DAMATLIK ATA YAKA TAİLORED SLİM FİT GÖMLEK",
                    "product_url" => "https://www.altinyildizclassics.com//p_damatlik-ata-yaka-tailored-slim-fit-gomlek_2571793_2824",
                    "original_price" => 499.99,
                    "code_product" => " 4A2019200207BYZ",
                    "category" => "category_name",
                ],
            ],
        ];
    }

    private function getProductOnePage($href): array
    {
        $data = [];
        foreach ($href as $cat => $url) { // 2 kategoriyalar
//            $data[$cat] = [];
            foreach ($url as $prod => $uri) { // 1 productalar
                $data[$cat . 'cat'][$prod . 'prod'] = \Arr::collapse($data[$cat . 'cat'][$prod . 'prod'] = $this->getResponse('.product-details', $uri)
                    ->each(function ($node) {
                        $array['name'] = $node->filter('.thintitle span')->eq(1)->text();
                        $array['product_url'] = $node->getUri();

                        if ($node->filter('.data')->children()->count() == 1) {
                            $array['original_price'] = $this->replaceStringToFloat($node->filter('.data span')->eq(0)->text());
                        } else {
                            $array['original_price'] = $this->replaceStringToFloat($node->filter('.data span')->eq(0)->text());
                            $array['sale_price'] = $this->replaceStringToFloat($node->filter('.data span')->eq(1)->text());
                            $array['discount'] = $array['original_price'] - $array['sale_price'];
                        }

                        $array['code_product'] = $this->getBetweenString($node->filter('.description li')->text());
                        $array['category'] = 'category_name';
                        $array['brand'] = 'AltinYildiz classics';

                        return $array;
                    }));
            }
        }
        return $data;
    }

}
