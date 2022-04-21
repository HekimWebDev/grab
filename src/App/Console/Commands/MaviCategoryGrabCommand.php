<?php

namespace App\Console\Commands;

use Domains\ServiceManagers\Mavi\MaviManager;
use Illuminate\Console\Command;
use Service\Mavi\Response;

class MaviCategoryGrabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mv:category:grab';

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
        $manager = new MaviManager();

        $arr = $manager->getCategories();

        $response = new Response($arr);

        \Illuminate\Support\Facades\Storage::disk('public')->put('\categories\Mavi.json', $response->getJson());

        $this->info('Grabing category from Mavi.com was successful!');
    }
}
