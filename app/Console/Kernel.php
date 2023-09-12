<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\GenerateDDDClasses;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // TODO check for scheduling time
        $schedule->command('attendance:store')
            ->everyTenMinutes()
            ->between('06:00', '19:00')
            ->appendOutputTo('scheduler.log');

        $schedule->command('absences:store')
            ->daily()
            ->at('22:00')
            ->appendOutputTo('scheduler.log');

        $schedule->command('daily:update-status')
            ->daily()
            ->at('22:00')
            ->appendOutputTo('UpdateStatusId.log');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        $this->load([
            GenerateDDDClasses::class,
        ]);

        require base_path('routes/console.php');
    }
}
