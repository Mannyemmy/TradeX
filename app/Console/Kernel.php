<?php

namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Settings;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('process:trades')->everyMinute();
        $schedule->command('queue:work --stop-when-empty --max-time=30')->everyMinute()->withoutOverlapping();
        $schedule->job(new \App\Jobs\CalculateCopyTradeProfit)->daily();
        $schedule->job(new \App\Jobs\GenerateBotTrades)->everyFiveMinutes();
        $schedule->job(new \App\Jobs\CalculateBotProfit)->hourly();

        // Price update schedules
        $schedule->command('prices:crypto')->everyFiveMinutes();
        $schedule->command('prices:market')->everyThreeHours()->withoutOverlapping(30);
        $schedule->command('stocks:sync-catalog')->dailyAt('02:00')->withoutOverlapping();
        $schedule->command('stocks:activate-popular')
            ->everyMinute()
            ->withoutOverlapping(120)
            ->appendOutputTo(storage_path('logs/stocks-activation.log'));
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
