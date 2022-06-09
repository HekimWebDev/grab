<?php

namespace App\Console\Commands;

use Domains\Prices\Models\Price;
use Domains\Products\Models\Product;
use Domains\ServiceManagers\Avva\AvvaManager;
use Illuminate\Console\Command;

class AvvaPriceGrabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'av:price:grab';

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
        $manager = new AvvaManager();

        $categories = $manager->getPageId();

        foreach ($categories as $key => $category){

            if ($category == null)
                continue;

            $item = 1;

            while (1) {

                $data = [];

                $pricesFromHtml = $manager->getPrices($category, $item);

                $this->info("$key) Avva: getting prices from - $category -> " . $item);

                foreach ($pricesFromHtml as $newPrices) {

                    $product = Product::whereInternalCode($newPrices['internal_code'])->with('price')->first();

                    if (!$product) {
                        continue;
                    }

                    $origin = $newPrices['original_price'];
                    $sale = $newPrices['sale_price'];

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

        $this->info('Avva: grabing prices was successful!');
    }
}
