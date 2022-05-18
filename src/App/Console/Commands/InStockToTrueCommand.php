<?php

namespace App\Console\Commands;

use Domains\Products\Models\Product;
use Illuminate\Console\Command;

class InStockToTrueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instock:true';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'In_stock to true';

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
        for ($i = 1; $i < 5; $i++ ) {
            Product::whereServiceType($i)
                ->has('price')
                ->update(['in_stock' => 1]);
        }
    }
}
