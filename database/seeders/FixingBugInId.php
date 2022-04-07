<?php

namespace Database\Seeders;

use Domains\Prices\Models\Price;
use Illuminate\Database\Seeder;

class FixingBugInId extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $products = \Domains\Products\Models\Product::whereServiceType(1)
            ->select('product_id', 'id')
            ->get()
            ->keyBy('product_id');

        foreach ($products as $key => $product){

            $product->internal_code = 'ay_' . $product->product_id;
            $product->save();

            Price::whereProductId($key)
                ->update([
                    'product_id' => $product->id,
                    'internal_code' => 'ay_' . $product->product_id
                ]);
        }
    }
}
