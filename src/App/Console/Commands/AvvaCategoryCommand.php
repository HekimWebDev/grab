<?php

namespace App\Console\Commands;

use Domains\ServiceManagers\Avva\AvvaManager;
use Illuminate\Console\Command;

class AvvaCategoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'av:category:grab';

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
        $manager = new AvvaManager();

        $data = $manager->getCategories();

        $response = new \Service\AltinYildiz\Response($data);

        \Illuminate\Support\Facades\Storage::disk('public')->put('\categories\Avva.json', $response->getJson());

        $this->info('Grabing category was successful!');
    }
}
