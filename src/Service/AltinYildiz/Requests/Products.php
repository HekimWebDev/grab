<?php

namespace Service\AltinYildiz\Requests;

use Domains\AltinYildiz\Actions\Category;
use Domains\Prices\Models\Price;
use Domains\Products\Models\Product;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Products extends Categories
{
    private string $suffix_url = '/?dropListingPageSize=5000';
    private string $prefix_url = 'https://www.altinyildizclassics.com/api/attributeselection/';

    /**
     * @throws GuzzleException
     */
    public function checkPrices(): array
    {
        $data = [];
        $product_id = Product::select('product_id', 'old_prices')->get();
//        dd($product_id);
        $client = new Client();
        foreach ($product_id as $prod => $id) {
            $request = $client->request('GET', $this->prefix_url . $id->product_id, ['http_errors' => false]);
            if ($request->getStatusCode() == 200) {
                $response = json_decode($request->getBody());

                $response_sale_price = $response->SalePrice;
                $current_sale_price = $id->price->sale_price != null ? $id->price->sale_price : $id->price->original_price;
//                $data[$prod] = $response_sale_price . ' - ' . $current_sale_price;
//
                if ($response_sale_price != $current_sale_price) {
                    $data[$prod] = [
                        'product_id' => $id->product_id,
                        'original_price' => $id->price->original_price,
                        'sale_price' => $response_sale_price,
                        'created_at' => date('Y-m-d h-i-s'), //2022-01-30 17:03:05
                        'updated_at' => date('Y-m-d h-i-s'),
                    ];
                    $id->update([
                        'old_prices' => $id->old_prices += 1,
                    ]);
                }
            } else {
                dump($request->getStatusCode() . ' Osipka');
                $id->update([
                    'in_stock' => 0
                ]);
            }

        }
        return $data;
    }

    public function getProducts(): array
    {

//        $categories = $this->getJsonCategories();
//        $categories = json_decode($categories);

        $categories = new Category();
        $categories = $categories->getSubCategories();

        $data = [];
        foreach ($categories as $cat => $page_list) {
            dump($this->url . $page_list);
            $data[$cat.'prod'] = $this->getResponse('.listing-list .description', $page_list . $this->suffix_url)
                ->each(function ($node) {
                $product['product_id'] = intval($node->filter('a')->attr('data-id'));
                $product['name'] = $node->filter('h2')->text();
//                $product['product_url'] = $node->filter('a')->attr('href');
                $product['product_code'] = $node->filter('a')->attr('data-code');

                $product['category_name'] = 'category_name';
                $product['service_type'] = 1;
                $product['created_at'] = date('Y-m-d H-i-s'); //2022-01-30 17:03:05
                $product['updated_at'] = date('Y-m-d H-i-s');

                return $product;
            });
            $data[$cat.'price'] = $this->getResponse('.listing-list .description', $page_list . $this->suffix_url)
                ->each(function ($node) {
                    $product['product_id'] = intval($node->filter('a')->attr('data-id'));
                    if ($node->filter('.data')->children()->count() < 2) {
                        $product['original_price'] = $this->replaceStringToFloat($node->filter('.data span')->text());
                        $product['sale_price'] = $this->replaceStringToFloat($node->filter('.data span')->text());
//                    $product['discount'] = null;
                    } else {
                        $product['original_price'] = $this->replaceStringToFloat($node->filter('.data span')->eq(0)->text());
                        $product['sale_price'] = $this->replaceStringToFloat($node->filter('.data span')->eq(1)->text());
                    }
                    $product['created_at'] = date('Y-m-d h-i-s'); //2022-01-30 17:03:05
                    $product['updated_at'] = date('Y-m-d h-i-s');

                    return $product;
                });
        }
        return $data;
//        return $this->getProductPage($categories);

    }

    private function replaceStringToFloat($code): float
    {
        $code = \Str::before($code, 'TL');
        return floatval(str_replace(',', '.', $code));
    }

}
