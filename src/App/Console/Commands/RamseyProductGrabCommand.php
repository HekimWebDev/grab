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
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        DB::transaction(function (){
            $manager = new RamseyManager();

            $categories = $manager->getUrlsForGrab();

            foreach ($categories as $category){

                $this->info("Ramsey: Grabing products from - $category");

                $products = $manager->getProducts($category);

                Product::upsert($products, ['product_id']);

                $count = count($products);

                $this->info("$count upserted");
            }
        });

        $this->info('Ramsey: Grabing products was successful!');
    }
}
