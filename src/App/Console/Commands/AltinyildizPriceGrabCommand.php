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

        $categories = Product::select('category_url')
                        ->groupBy('category_url')
                        ->get()
                        ->map(fn($p) => $p->category_url)
                        ->toArray();

        foreach ($categories as $categoryUrl) {

            $products = Product::with('price')
                ->where('service_type', 1)
                ->where('in_stock', 1)
                ->whereCategoryUrl($categoryUrl)
                ->get()
                ->keyBy('product_id');

            $data = [];

            $this->info("getting prices from - $categoryUrl");

            $pricesFromHtml = $manager->getPrices($categoryUrl);

            foreach ($pricesFromHtml as $newPrices){

                if( !isset($products[$newPrices['product_id']]) ) {
                    continue;
                }

                $latestPrice = $products[$newPrices['product_id']]->price;

                $origin = ayLiraFormatter($newPrices['original_price']);
                $sale = ayLiraFormatter($newPrices['sale_price']);

                if (empty($latestPrice) || $latestPrice->original_price != $origin || $latestPrice->sale_price != $sale){
                    $data[] = [
                        'product_id'     => $newPrices['product_id'],
                        'original_price' => $origin,
                        'sale_price'     => $sale,
                        'created_at'     => now(), //2022-01-30 17:03:05
                        'updated_at'     => now(),
                    ];
                }

                $products[$newPrices['product_id']]->touch();
            }

            $count = count($data);

            Price::insert($data);

            $this->info("$count prices inserted");
        }

        $this->info('grabing prices was successful!');
    }
}
