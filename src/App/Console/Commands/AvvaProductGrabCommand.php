<?php

namespace App\Console\Commands;

use Domains\Products\Models\Product;
use Domains\ServiceManagers\Avva\AvvaManager;
use Illuminate\Console\Command;

class AvvaProductGrabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'av:product:grab';

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
        $manager = new AvvaManager();

        $categories = $manager->getPageId();

        Product::whereServiceType(5)
            ->where('in_stock', 1)
            ->update(['in_stock' => 0]);

        foreach ($categories as $key => $category){

            if ($category == null)
                continue;

            $item = 0;

            while (1){

                $products = $manager->getProducts($category, $item);

                $this->info("$key) Avva: Grabing products from - $category -> " . $item);

                Product::upsert($products, ['internal_code', 'service_type'], ['name', 'category_url', 'product_url', 'in_stock']);

                $count = count($products);

                $this->info("$count upserted");

                if (empty($products))
                    break;

                $item++;
            }
        }

        $this->info('Avva: Grabing products was successful!');
    }
}
