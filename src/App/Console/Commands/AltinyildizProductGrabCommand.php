<?php

namespace App\Console\Commands;

use Domains\ServiceManagers\AltinYildiz\AltinYildizManager;
use Illuminate\Console\Command;

class AltinyildizProductGrabCommand extends Command
{
    protected $signature = 'ay:products:grab';

    protected $description = 'Проверка данных каждую неделю';

    public function handle(): void
    {
        $client = new AltinYildizManager();
        $client->createProducts();
    }
}
