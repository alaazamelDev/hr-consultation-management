<?php

namespace App\Application\Http\Controllers;

use App\Domain\Models\CD\Consultant;

class ConsultantMobileNotificationController
{
    public function getAllNotifications()
    {
        $consultant_id = 1; // TODO : $customer_id = Auth::id();
        $consultant = Consultant::where('id', $consultant_id)->first();
        return $consultant->notifications;
    }

    public function getUnReadNotifications()
    {
        $consultant_id = 1; // TODO : $customer_id = Auth::id();
        $consultant = Consultant::where('id', $consultant_id)->first();
        return $consultant->unreadNotifications;
    }

    public function markNotificationAsRead($notificationId)
    {
        $consultant_id = 1; // TODO : $customer_id = Auth::id();
        $consultant = Consultant::where('id', $consultant_id)->first();
        $notification = $consultant->notifications->find($notificationId);

        if ($notification) {
            // Mark the notification as read
            $notification->markAsRead();
        }

        return $notification;
    }
}
