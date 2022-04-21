<?php

namespace App\Console\Commands;

use Domains\Products\Models\Product;
use Domains\ServiceManagers\Mavi\MaviManager;
use Illuminate\Console\Command;

class MaviProductGrabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mv:product_code:grab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get product codes form API';

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

        Product::whereServiceType(3)
            ->where('in_stock', 1)
            ->update(['in_stock' => 0]);

        foreach ($categories as $key => $category) {

            $item = 0;

            while (1){

                $products = $manager->getProducts($category . $item);

                $this->info("Mavi: Grabing products from - $category" . $item);

                Product::upsert($products, ['internal_code', 'service_type'], ['name', 'category_url', 'product_url', 'in_stock']);

                $count = count($products);

                $this->info("$count upserted");

                if (empty($products))
                    break;

                $item++;
            }

        }

        $this->info('Ramsey: Grabing products was successful!');
    }
}
