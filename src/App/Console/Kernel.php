<?php

namespace App\Console;

use App\Console\Commands\AltinyildizCategoryGabCommand;
use App\Console\Commands\AltinyildizPriceGrabCommand;
use App\Console\Commands\AltinyildizProductGrabCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AltinyildizCategoryGabCommand::class,
        AltinyildizProductGrabCommand::class,
        AltinyildizPriceGrabCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ay:category:grab')->weekly()->sundays()->at('01:00');
        $schedule->command('ay:products:grab')->dailyAt('02:00');
        $schedule->command('ay:price:grab')->dailyAt('05:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
