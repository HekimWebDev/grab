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
