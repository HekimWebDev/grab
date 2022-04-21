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

        $products = Product::whereServiceType(3)
            ->where('in_stock', 1)
            ->with('price')
            ->select('id', 'internal_code')
            ->get()
            ->keyBy('internal_code');

        foreach ($categories as $key => $categoryUrl) {

            $item = 0;

            while (1) {

                $data = [];

                $pricesFromHtml = $manager->getPrices($categoryUrl . $item);

                $this->info("Mavi: getting prices from - $categoryUrl" . $item . " => " . count($pricesFromHtml));

                foreach ($pricesFromHtml as $newPrices) {

                    if (!isset($products[$newPrices['internal_code']])) {
                        continue;
                    }

                    $latestPrice = $products[$newPrices['internal_code']]->price;

                    $origin = $newPrices['original_price'];
                    $sale = $newPrices['sale_price'];

                    if (empty($latestPrice) || $latestPrice->original_price != $origin || $latestPrice->sale_price != $sale) {

                        $data[] = [
                            'product_id' => $products[$newPrices['internal_code']]->id,
                            'internal_code' => $newPrices['internal_code'],
                            'original_price' => $origin,
                            'sale_price' => $sale,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    $products[$newPrices['internal_code']]->touch();
                }

                $count = count($data);

                Price::insert($data);

                $this->info("$count prices inserted");

                if (empty($pricesFromHtml))
                    break;

                $item++;
            }
        }

        $this->info('Mavi: grabing prices was successful!');
    }
}
