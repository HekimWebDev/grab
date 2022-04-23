<?php

namespace App\Console\Commands;

use Domains\Prices\Models\Price;
use Domains\Products\Models\Product;
use Domains\ServiceManagers\Mavi\MaviManager;
use Illuminate\Console\Command;

class MaviPriceGrabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mv:price:grab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get prices form API';

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
        $manager = new MaviManager();

        $categories = $manager->getUrl();

        foreach ($categories as $key => $categoryUrl) {

            $item = 0;

            while (1) {

                $data = [];

                $pricesFromHtml = $manager->getPrices($categoryUrl . $item);

                $this->info("Mavi: getting prices from - $categoryUrl" . $item);

                foreach ($pricesFromHtml as $newPrices) {

                    $product = Product::whereInternalCode($newPrices['internal_code'])->with('price')->first();

                    if (!$product) {
                        continue;
                    }

                    $origin = ayLiraFormatter($newPrices['original_price']);
                    $sale = ayLiraFormatter($newPrices['sale_price']);

                    if (empty($product->price) || $product->price->original_price != $origin || $product->price->sale_price != $sale) {
                        $data[] = [
                            'product_id'     => $product->id,
                            'internal_code'  => $newPrices['internal_code'],
                            'original_price' => $origin,
                            'sale_price'     => $sale,
                            'created_at'     => now(),
                            'updated_at'     => now(),
                        ];
                    }

                    $product->touch();
                }

                $count = count($data);

                Price::insert($data);

                $this->info("Got " . count($pricesFromHtml) . " results, $count prices inserted!");

                if (empty($pricesFromHtml))
                    break;

                $item++;
            }
        }

        $this->info('Mavi: grabing prices was successful!');
    }
}