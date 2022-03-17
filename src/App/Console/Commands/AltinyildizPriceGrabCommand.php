<?php

namespace App\Console\Commands;

use Domains\ServiceManagers\AltinYildiz\AltinYildizManager;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class AltinyildizPriceGrabCommand extends Command
{
    protected $signature = 'ay:price:grab';

    protected $description = 'Grab prices from HTML';

    /**
     * @throws GuzzleException
     */
    public function handle()
    {
        $client = new AltinYildizManager();
        $client->updatePrice();

        $this->info('Grabing prices was successful!');
    }
}
