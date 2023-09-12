<?php

namespace App\Events;

use App\Domain\Models\CD\Appointment;
use App\Domain\Models\CD\AppointmentStatus;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppointmentSaving
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }

    public function handle(Appointment $appointment): void
    {
        // Your logic to update the status_id attribute here
        if ($appointment->workDay->day_date < now() && $appointment->status_id == AppointmentStatus::STATUS_AVAILABLE) {
            $appointment->status_id = AppointmentStatus::STATUS_CLOSED;
        } elseif ($appointment->workDay->day_date < now() && $appointment->status_id == AppointmentStatus::STATUS_RESERVED) {
            $appointment->status_id = AppointmentStatus::STATUS_ATTENDANCE_IS_NOT_RECORDED;
        }
    }
}
