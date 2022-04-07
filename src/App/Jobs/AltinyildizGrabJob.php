<?php

namespace App\Jobs;

use Domains\Prices\Models\Price;
use Domains\Products\Models\Product;
use Domains\ServiceManagers\AltinYildiz\AltinYildizManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AltinyildizGrabJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = 3;

    protected $product;

    protected $manager;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->manager = new AltinYildizManager();
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pricesFromHtml = $this->manager->checkPrice($this->product->category_url);

        if( isset($pricesFromHtml[$this->product->product_id]) ) {

            $newPrices = $pricesFromHtml[$this->product->product_id];

            $oldPrices = $this->product->price;

            $originPrice = ayLiraFormatter($newPrices['original_price']);

            $salePrice = ayLiraFormatter($newPrices['sale_price']);

            if (empty($oldPrices) || !($oldPrices->original_price == $originPrice && $oldPrices->sale_price == $salePrice)) {

                Price::create([
                    'product_id' => $this->product->id,
                    'original_price' => $originPrice,
                    'sale_price' => $salePrice
                ]);
            }
        }

        $this->product->touch();
    }
}
