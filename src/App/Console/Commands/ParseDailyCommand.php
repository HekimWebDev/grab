<?php

namespace App\Console\Commands;

use Domains\AltinYildiz\Actions\AltinYildizManager;
use Domains\AltinYildiz\Actions\CreateAltinYildizActions;
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
        $client->updatePrices();
    }
}
