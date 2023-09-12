<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCancelledByConsultant extends Notification
{
    protected $consultantId;
    protected $consultantName;
    protected $appointmentDate;

    public function __construct($consultantId,$consultantName,$appointmentDate)
    {
        $this->consultantId = $consultantId;
        $this->consultantName = $consultantName;
        $this->appointmentDate = $appointmentDate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Use the database channel
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'المختص ذو المعرف "'.$this->consultantId .'" صاحب الاسم "' . $this->consultantName . '" قام بإلغاء الحجز الخاص بك المقرر بتاريخ "' . $this->appointmentDate . '".',
        ];
    }
}
