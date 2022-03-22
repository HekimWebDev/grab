<?php

namespace App\Console\Commands;

use Domains\Products\Models\Product;
use Domains\ServiceManagers\AltinYildiz\AltinYildizManager;
use Illuminate\Console\Command;

class AltinyildizProductGrabCommand extends Command
{
    protected $signature = 'ay:products:grab';

    protected $description = 'Grab products from HTML';

    public function handle(): void
    {
        $manager = new AltinYildizManager();

        $categories = $manager->getSubCategoriesForGrab();
//        dd($categories);
        Product::where('in_stock', 1)
            ->update(['in_stock' => 0]);

        foreach ( $categories as $category) {

            $this->info("Grabing products from - $category");

            $products = $manager->getProducts([$category]);

            Product::upsert($products, ['product_id']);

            $count = count($products);

            $this->info("$count upserted");
        }

        $this->info('Grabing products was successful!');
    }
}
