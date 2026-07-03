<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
   protected $commands = [
    \App\Console\Commands\ManageSubscriptions::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('sms:low-balance-alert')
                ->dailyAt('09:00')        // runs every day at 9 AM
                ->withoutOverlapping()    // prevent double run if previous is still running
                ->runInBackground();

        $schedule->command('preorders:send-mails')
             ->everyFiveMinutes()
             ->withoutOverlapping();
    }


    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
