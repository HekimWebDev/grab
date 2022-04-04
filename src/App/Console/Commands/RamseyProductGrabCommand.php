<?php

namespace App\Console\Commands;

use Domains\Products\Models\Product;
use Domains\ServiceManagers\Ramsey\RamseyManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RamseyProductGrabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rs:product:grab';

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
    public function handle(): void
    {
        DB::transaction(function () {

            $manager = new RamseyManager();

            $categories = $manager->getUrlsForGrab();

            Product::whereServiceType(2)
                ->where('in_stock', 1)
                ->update(['in_stock' => 0]);

            foreach ($categories as $key => $category) {

                $this->info("Ramsey: Grabing products from - $category");

                $products = $manager->getProducts($category);

                Product::upsert($products, ['product_id', 'service_type']);

                $count = count($products);

                $this->info("$count upserted");
            }
        });

        $this->info('Ramsey: Grabing products was successful!');
    }
}
