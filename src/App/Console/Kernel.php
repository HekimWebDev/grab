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
        $schedule->command('ay:product:grab')->dailyAt('01:30');
        $schedule->command('ay:price:grab')->dailyAt('02:30');

//        $schedule->command('rs:product:grab')->dailyAt('03:00');
//        $schedule->command('rs:price:grab')->dailyAt('03:20');
//        $schedule->command('rs:product_code:grab')->dailyAt('03:50');


        $schedule->command('mv:category:grab')->weekly()->sundays()->at('04:25');
        $schedule->command('mv:product:grab')->dailyAt('04:30');
        $schedule->command('mv:price:grab')->dailyAt('05:00');

        $schedule->command('kt:category:grab')->weekly()->sundays()->at('05:10');
        $schedule->command('kt:product:grab')->dailyAt('05:15');
        $schedule->command('kt:price:grab')->dailyAt('06:00');

        $schedule->command('av:category:grab')->weekly()->sundays()->at('06:30');
        $schedule->command('av:product:grab')->dailyAt('06:45');
        $schedule->command('av:price:grab')->dailyAt('07:00');

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
