<?php

namespace App\Console\Commands;

use Domains\Products\Models\Product;
use Domains\ServiceManagers\Ramsey\RamseyManager;
use Illuminate\Console\Command;

class RamseyProductCodeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rs:product_code:grab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get product codes form HTML';

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
        $manager = new RamseyManager();

        $products = Product::whereServiceType(2)
            ->where('product_code', 'like', '%ramsey%')
//            ->whereProductCode('%ramsey%')
            ->select('id', 'product_id', 'product_code', 'product_url')
            ->get();

        $count = count($products);

        $this->info($count . ' - products!');

        foreach ($products as $key => $product){

            $product->product_code = $manager->getProductCode($product->product_url);

            $this->info($count - $key . ") Ramsey: $product->product_id");

            $product->save();
        }

        $this->info('Ramsey: Grabing product codes was successful!');
    }
}
