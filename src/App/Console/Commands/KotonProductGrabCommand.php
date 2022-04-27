<?php

namespace App\Console\Commands;

use Domains\Products\Models\Product;
use Domains\ServiceManagers\Koton\KotonManager;
use Illuminate\Console\Command;

class KotonProductGrabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kt:product:grab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get products form HTML';

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
        $manager = new KotonManager();

        $categoryies = $manager->getUrl();

        Product::whereServiceType(4)
            ->where('in_stock', 1)
            ->update(['in_stock' => 0]);

        foreach ($categoryies as $key => $category){

            $countFromHtml = $manager->getProductCount($category);

            if ($countFromHtml == -1)
                continue;

            $producsCount = 0;

            $item = 0;

            while(1){

                $products = $manager->getProducts($category . "?q=%3Arelevance&psize=500&page=$item");

                $this->info("Koton: Grabing products from - $category" . "?q=%3Arelevance&psize=500&page=$item");

                Product::upsert($products, ['internal_code', 'service_type'], ['name', 'category_url', 'product_url', 'in_stock']);

                $count = count($products);

                $producsCount += $count;

                $this->info("$count upserted");

                if (empty($products) || $producsCount >= $countFromHtml){
                    break;
                }

                $item++;
            }
        }

        $this->info('Koton: Grabing products was successful!');
    }
}
