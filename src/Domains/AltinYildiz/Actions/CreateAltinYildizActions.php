<?php

namespace Domains\AltinYildiz\Actions;

use Domains\Prices\Models\Price;
use Domains\Products\Models\Product;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use Service\AltinYildiz\Requests\Products;

class CreateAltinYildizActions
{
    protected $startTime;

    public function createProductsEveryWeek()
    {
        $products = new Products();
        $products = $products->getProducts();

//        dd($products);
        foreach ($products as $key => $product) {
            foreach ($product as $k => $item) {
                Product::firstOrCreate(['product_code' => $item['product_code']], $item);
                Price::firstOrCreate(['product_id' => $item['product_id']], $item);
            }
        }
    }

    /**
     * @throws GuzzleException
     */
    public function checkDailyPrices($id = null)
    {
        $this->regTime(1);
        $product = new Products();
        $products = $product->checkPrices2($id);

        if (!empty($products)) {
            Price::insert($products);
        }
        $this->regTime();
    }

    private function regTime($status = null)
    {
        if ($status) {
            if (empty($this->startTime)) $this->startTime = new \DateTime('now');
        } else {
            $endTime = new \DateTime('now');
            $interval = $this->startTime->diff($endTime);
            $this->startTime = '';
            return dump($interval->format('%i минута, %S секунд, %f  микросекунд'));
        }
        return null;
    }

}
