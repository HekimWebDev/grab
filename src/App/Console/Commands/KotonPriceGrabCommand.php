<?php

namespace App\Console\Commands;

use Domains\Prices\Models\Price;
use Domains\Products\Models\Product;
use Domains\ServiceManagers\Koton\KotonManager;
use Illuminate\Console\Command;

class KotonPriceGrabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kt:price:grab';


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

    public function handle()
    {
        $manager = new KotonManager();

        $categoryies = $manager->getUrl();

        foreach ($categoryies as $key => $category){

            $countFromHtml = $manager->getProductCount($category);

//            item = 233

            if ($countFromHtml == -1)
                continue;

            $producsCount = 0;

            $item = 0;

            while(1){

                $data = [];

                $products = Product::select('id', 'internal_code', 'product_id')
                    ->where('category_url', 'like', "$category?q=%3Arelevance&psize=500&page=$item")
                    ->whereInStock(1)
                    ->with('price')
                    ->get()
                    ->keyBy('internal_code');

                $prices = $manager->getPrices($category . "?q=%3Arelevance&psize=500&page=$item");

                $this->info("$key) " . "Koton: Grabing products from - $category" . "?q=%3Arelevance&psize=500&page=$item");

                foreach ($prices as $price){

                    if(!isset($products[$price['internal_code']]))
                        continue;

                    $latestPrice = $products[$price['internal_code']]->price;

                    $origin = ktLiraFormatter($price['original_price']);
                    $sale = ktLiraFormatter($price['sale_price']);

                    if (empty($latestPrice) || $latestPrice->original_price != $origin || $latestPrice->sale_price != $sale){

                        $data[] = [
                            'product_id'        => $products[$price['internal_code']]->id,
                            'internal_code'     => $price['internal_code'],
                            'original_price'    => $origin,
                            'sale_price'        => $sale,
                            'created_at'        => now(),
                            'updated_at'        => now(),
                        ];

                        $products[$price['internal_code']]->touch();
                    }
                }

                $count = count($prices);

                $producsCount += $count;

                Price::insert($data);

                $this->info("Got " . $count . " results, " . count($data) . " prices inserted!");

                if (empty($prices) || $producsCount >= $countFromHtml){
                    break;
                }

                $item++;
            }
        }

        $this->info('Koton: grabing prices was successful!');
    }
}
