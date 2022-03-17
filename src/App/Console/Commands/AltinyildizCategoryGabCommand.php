<?php

namespace App\Console\Commands;

use Domains\AltinYildiz\Actions\Category;
use Domains\ServiceManagers\AltinYildiz\AltinYildizManager;
use http\Client\Response;
use Illuminate\Console\Command;

class AltinyildizCategoryGabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ay:category:grab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grab urls of categories from HTML';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $begin = time();
        $code = new AltinYildizManager();
        $data = $code->grabCategoriesTreeFromHtml();
//        $end = time();
        $response = new \Service\AltinYildiz\Response($data);
        \Illuminate\Support\Facades\Storage::disk('public')->put('\categories\AltinYildiz.json', $response->getJson());

        $this->info('Grabing category tree was successful!');
    }
}
