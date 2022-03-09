<?php

namespace App\Console\Commands;

use Domains\ServiceManagers\AltinYildiz\AltinYildizManager;
use Illuminate\Console\Command;

class ParseEveryWeekCommand extends Command
{
    protected $signature = 'parse:week';

    protected $description = 'Проверка данных каждую неделю';

    public function handle(): void
    {
        $client = new AltinYildizManager();
        $client->createProductsEveryWeek();
    }
}
