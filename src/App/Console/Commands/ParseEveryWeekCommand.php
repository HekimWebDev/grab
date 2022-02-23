<?php

namespace App\Console\Commands;

use Domains\AltinYildiz\Actions\CreateAltinYildizActions;
use Illuminate\Console\Command;

class ParseEveryWeekCommand extends Command
{
    protected $signature = 'parse:week';

    protected $description = 'Проверка данных каждую неделю';

    public function handle(): void
    {
        $client = new CreateAltinYildizActions();
        $client->createProductsEveryWeek();
    }
}
