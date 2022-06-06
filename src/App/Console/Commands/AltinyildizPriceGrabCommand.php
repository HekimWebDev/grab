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

        $categories = Product::whereServiceType(1)
            ->select('category_url')
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
                ->keyBy('internal_code');

            $data = [];

            $this->info("Altinyildiz: getting prices from - $categoryUrl");

            $pricesFromHtml = $manager->getPrices($categoryUrl);

            foreach ($pricesFromHtml as $newPrices){

                if( !isset($products[$newPrices['internal_code']]) ) {
                    continue;
                }

                $latestPrice = $products[$newPrices['internal_code']]->price;

                $origin = ayLiraFormatter($newPrices['original_price']);
                $sale = ayLiraFormatter($newPrices['sale_price']);

                if (empty($latestPrice) || $latestPrice->original_price != $origin || $latestPrice->sale_price != $sale){
                    $data[] = [
                        'product_id'        => $products[$newPrices['internal_code']]->id,
                        'internal_code'     => $products[$newPrices['internal_code']]->internal_code,
                        'original_price'    => $origin,
                        'sale_price'        => $sale,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ];
                }

                $products[$newPrices['internal_code']]->touch();
            }

            $count = count($data);

            Price::insert($data);

            $this->info("$count prices inserted");
        }

        $this->info('grabing prices was successful!');
    }
}
