<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Domains\Products\Models\Product;
use Domains\ServiceManagers\AltinYildiz\AltinYildizManager;
use Illuminate\Console\Command;

class AltinyildizProductGrabCommand extends Command
{
    protected $signature = 'ay:product:grab';

    protected $description = 'Grab products from HTML';

    public function handle(): void
    {
        DB::transaction(function (){

            $manager = new AltinYildizManager();

            $categories = $manager->getSubCategoriesForGrab();

            Product::whereServiceType(1)
                ->where('in_stock', 1)
                ->update(['in_stock' => 0]);

            foreach ( $categories as $category) {

                $this->info("Altinyildiz: Grabing products from - $category");

                $products = $manager->getProducts([$category]);

                Product::upsert($products, ['internal_code', 'service_type'], ['name', 'category_url', 'product_url', 'in_stock']);
//                Product::upsert($products, ['product_id', 'internal_code'], ['name', 'category_url', 'product_url', 'in_stock']);

                $count = count($products);

                $this->info("$count upserted");
            }
        });

        $this->info('Altinyildiz: Grabing products was successful!');
    }
}
