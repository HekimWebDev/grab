<?php

namespace App\Console\Commands;

use Domains\ServiceManagers\Ramsey\RamseyManager;
use Illuminate\Console\Command;
use Service\Ramsey\Response;

class RamseyCategoryGrabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rs:category:grab';

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
        $manager = new RamseyManager();

        $data = $manager->getCategories();

        $response = new Response($data);

        \Illuminate\Support\Facades\Storage::disk('public')->put('\categories\Ramsey.json', $response->getJson());

        $this->info('Grabing category was successful!');
    }
}
