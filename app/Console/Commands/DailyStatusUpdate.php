<?php

namespace App\Console\Commands;

use App\Domain\Models\CD\Appointment;
use App\Domain\Models\CD\AppointmentStatus;
use App\Domain\Models\CD\WorkDay;
use Carbon\Carbon;
use Illuminate\Console\Command;

class
DailyStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Status id for appointments';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        $now = Carbon::now();

        $work_day = WorkDay::query()->where('day_date', '<', $now->toDateString())->get();
        // Query and update appointments based on your criteria
        $appointment = $work_day->appointments ;

        foreach ($appointment as $app) {
            if ($app->status_id == AppointmentStatus::STATUS_AVAILABLE) {
                $app->status_id = AppointmentStatus::STATUS_CLOSED;
                $app->save();
            }
            if ($app->status_id == AppointmentStatus::STATUS_RESERVED) {
                $app->status_id = AppointmentStatus::STATUS_ATTENDANCE_IS_NOT_RECORDED;
                $app->save();
            }
        }

        $this->info('Appointment statuses updated successfully.');
    }
}
