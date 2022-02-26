<?php

namespace App\Console;

use App\Console\Commands\CategoryCommand;
use App\Console\Commands\ParseDailyCommand;
use App\Console\Commands\ParseEveryWeekCommand;
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
        CategoryCommand::class
        ParseEveryWeekCommand::class,
        ParseDailyCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('parse:week')->weeklyOn(5, '2:00');
         $schedule->command('parse:daily')->dailyAt('6:00');
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
