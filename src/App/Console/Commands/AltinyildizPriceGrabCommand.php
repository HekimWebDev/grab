<?php

namespace App\Console\Commands;

use Domains\Prices\Models\Price;
use Domains\Products\Models\Product;
use Domains\ServiceManagers\AltinYildiz\AltinYildizManager;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class AltinyildizPriceGrabCommand extends Command
{
    protected $signature = 'ay:price:grab';

    protected $description = 'Grab prices from HTML';

    /**
     * @throws GuzzleException
     */
    public function handle()
    {
        $manager = new AltinYildizManager();

        $products = Product::where('service_type', 1)
            ->where('in_stock', 1)
            ->get()
            ->groupBy('category_url')
            ->map(function ($q){
                return $q->keyBy('product_id');
            });

        foreach ($products as $categoryUrl => $product) {

            $data = [];

            $this->info("getting prices from - $categoryUrl");

            $pricesFromHtml = $manager->getPrices($categoryUrl);
            
            foreach ($pricesFromHtml as $newPrices){

                if( !isset($product[$newPrices['product_id']]) ) {
                    return;
                }

                $oldPrices = $product[$newPrices['product_id']]->price;

                $nOriginPrice = liraCast($newPrices['original_price']);
                $nSalePrice = liraCast($newPrices['sale_price']);

                if (empty($oldPrices) || !($oldPrices->original_price == $nOriginPrice && $oldPrices->sale_price == $nSalePrice) ){
                    $data[] = [
                        'product_id'     => $newPrices['product_id'],
                        'original_price' => $nOriginPrice,
                        'sale_price'     => $nSalePrice,
                        'created_at'     => now(), //2022-01-30 17:03:05
                        'updated_at'     => now(),
                    ];
                }

                $product[$newPrices['product_id']]->touch();
            }

            $count = count($data);

            Price::insert($data);

            $this->info("$count prices inserted");
        }

        $this->info('grabing prices was successful!');
    }
}
