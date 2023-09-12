<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
class ReservationCancelledByCustomer extends Notification
{

    protected $customerId;
    protected $customerName;
    protected $appointmentDate;

    public function __construct($customerId,$customerName,$appointmentDate)
    {
        $this->customerId = $customerId;
        $this->customerName = $customerName;
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
            'message' => 'الزبون ذو المعرف "'.$this->customerId .'" صاحب الاسم "' . $this->customerName . '" قام بإلغاء حجز الموعد الخاص به المقرر بتاريخ ".'.$this->appointmentDate.'" .',
        ];
    }
}
