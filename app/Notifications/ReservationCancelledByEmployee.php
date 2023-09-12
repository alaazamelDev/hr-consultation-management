<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCancelledByEmployee extends Notification
{
    protected $employeeId;
    protected $employeeName;
    protected $appointmentDate;
    protected $status;
    protected $customerName;

    public function __construct($employeeId,$employeeName,$appointmentDate,$status,$customerName = null)
    {
        $this->employeeId = $employeeId;
        $this->employeeName = $employeeName;
        $this->appointmentDate = $appointmentDate;
        $this->status = $status;
        $this->customerName = $customerName;
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
        if($this->status == 0) { // notify consultant
            return [
                'message' =>
                    'الموظف ذو المعرف "' . $this->employeeId . '" صاحب الاسم "' . $this->employeeName . '" قام بإلغاء الحجز الخاص بالمستفيد "' . $this->customerName . '" المقرر بتاريخ "' .$this->appointmentDate . '".',
            ];
        }
        else // notify customer
            return [
            'message' =>
                'الموظف ذو المعرف "' . $this->employeeId . '" صاحب الاسم "' . $this->employeeName . '" قام بإلغاء الحجز الخاص بك المقرر بتاريخ "' . $this->appointmentDate . '".',
            ];
    }
}
