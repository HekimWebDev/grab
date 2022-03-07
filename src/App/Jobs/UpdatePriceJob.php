<?php
 
namespace App\Jobs;

use Domains\AltinYildiz\Actions\CreateAltinYildizActions;
use Domains\Products\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
 
class UpdatePriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 
    /**
     * The podcast instance.
     *
     * @var \App\Models\Podcast
     */
    protected $product;
 
    /**
     * Create a new job instance.
     *
     * @param  Domain\Models\Podcast  $podcast
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
 

    public function handle()
    {
        $client = new CreateAltinYildizActions();
        $client->checkDailyPrices($this->product->id);
        $product->update_completed = true;
        $product->save();
    }
}