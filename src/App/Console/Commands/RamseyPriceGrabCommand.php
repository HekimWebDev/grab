<?php

namespace App\Console\Commands;

use Domains\Prices\Models\Price;
use Domains\Products\Models\Product;
use Domains\ServiceManagers\Ramsey\RamseyManager;
use Illuminate\Console\Command;

class RamseyPriceGrabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rs:price:grab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grab prices from HTML';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $manager = new RamseyManager();

        $categories = $manager->getUrlsForGrab();

        foreach ($categories as $key => $categoryUrl) {

            $products = Product::whereServiceType(2)
                ->where('in_stock', 1)
                ->whereCategoryUrl($categoryUrl)
                ->with('price')
                ->get()
                ->keyBy('product_id');

            $data = [];

            $this->info("Ramsey: getting prices from - $categoryUrl");

            $pricesFromHtml = $manager->getPrices($categoryUrl);

            foreach ($pricesFromHtml as $newPrices) {

                if (!isset($products[$newPrices['product_id']])) {
                    continue;
                }

                $latestPrice = $products[$newPrices['product_id']]->price;

                $origin = ayLiraFormatter($newPrices['original_price']);
                $sale = ayLiraFormatter($newPrices['sale_price']);

                if (empty($latestPrice) || $latestPrice->original_price != $origin || $latestPrice->sale_price != $sale){

                    $data[] = [
                        'product_id'        => $newPrices['product_id'],
                        'internal_code'     => $products[$newPrices['product_id']]->inaaternal_code,
                        'original_price'    => $origin,
                        'sale_price'        => $sale,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ];
                }

                $products[$newPrices['product_id']]->touch();
            }

            $count = count($data);

            Price::insert($data);

            $this->info("$count prices inserted");
        }

        $this->info('Ramsey: grabing prices was successful!');
    }
}
