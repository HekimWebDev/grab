<?php

namespace App\Console\Commands;

use Domains\ServiceManagers\Koton\KotonManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Service\Koton\Response;

class KotonCategoryGrabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kt:category:grab';

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
        $manager = new KotonManager();

        $url = $manager->getCategories();

        $response = new Response($url);

        Storage::disk('public')->put('\categories\Koton.json', $response->getJson());

        $this->info("Grabing category from Koton.com was successful!");
    }
}
