<?php

namespace App\Console\Commands;

use Domains\ServiceManagers\Avva\AvvaManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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

//        get category ulr and write it to AvvaUrls.json
        $data = $manager->getCategories();

        $response = new \Service\Avva\Response($data);

        \Illuminate\Support\Facades\Storage::disk('public')->put('\categories\AvvaUrls.json', $response->getJson());

        $this->info('Grabing category was successful!');
        $this->info('...');

//        get pageId for each url
        foreach ($data as $url){
            $pagesId[] = $manager->getPageIdFromHtml($url);
        }

        Storage::disk('public')->put('\categories\AvvaPagesId.json', $response->getJson($pagesId));

        $this->info('Grabing PageId was successful!');
    }
}
