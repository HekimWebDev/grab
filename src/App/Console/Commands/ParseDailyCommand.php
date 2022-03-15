<?php

namespace App\Console\Commands;

use Domains\ServiceManagers\AltinYildiz\AltinYildizManager;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class ParseDailyCommand extends Command
{
    protected $signature = 'parse:daily';

    protected $description = 'Ежедневная проверка цен продуктов';

    /**
     * @throws GuzzleException
     */
    public function handle()
    {
        $client = new AltinYildizManager();
        $client->updatePriceYsmayyl();
    }
}
