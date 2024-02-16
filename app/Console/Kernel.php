<?php

namespace App\Console;

use App\Jobs\DeleteCachedSampleReportsJob;
use App\Jobs\DeleteOldAirdropFiles;
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

    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('backup:clean')->dailyAt('01:30');
        $schedule->command('backup:run')->dailyAt('01:35');
        $schedule->job(new DeleteCachedSampleReportsJob())->hourly();
        $schedule->job(new DeleteOldAirdropFiles())->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Console/Commands');

        require base_path('routes/console.php');
    }
}
