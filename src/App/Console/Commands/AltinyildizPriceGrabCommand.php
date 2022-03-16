<?php

namespace App\Console\Commands;

use Domains\ServiceManagers\AltinYildiz\AltinYildizManager;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class AltinyildizPriceGrabCommand extends Command
{
    protected $signature = 'ay:price:grab';

    protected $description = 'Ежедневная проверка цен продуктов';

    /**
     * @throws GuzzleException
     */
    public function handle()
    {
        $client = new AltinYildizManager();
        $client->updatePrice();
    }
}
