<?php

namespace App\Console\Commands;

use Domains\AltinYildiz\Actions\CreateAltinYildizActions;
use Illuminate\Console\Command;

class ParseEveryWeekCommand extends Command
{
    protected $signature = 'parse:week';

    protected $description = 'Data check every week';

    public function handle(): void
    {
        $client = new CreateAltinYildizActions();
        $client->createProductsEveryWeek();
    }
}
