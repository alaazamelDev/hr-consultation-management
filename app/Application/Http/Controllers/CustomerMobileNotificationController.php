<?php

namespace App\Application\Http\Controllers;

use App\Domain\Models\CD\Customer;

class CustomerMobileNotificationController extends Controller
{
    public function getAllNotifications()
    {
        $customer_id = 1; // TODO : $customer_id = Auth::id();
        $customer = Customer::where('id', $customer_id)->first();
        return $customer->notifications;
    }

    public function getUnReadNotifications()
    {
        $customer_id = 1; // TODO : $customer_id = Auth::id();
        $customer = Customer::where('id', $customer_id)->first();
        return $unreadNotifications = $customer->unreadNotifications;
    }

    public function markNotificationAsRead($notificationId)
    {
        $customer_id = 1; // TODO : $customer_id = Auth::id();
        $customer = Customer::where('id', $customer_id)->first();
        $notification = $customer->notifications->find($notificationId);

        if ($notification) {
            // Mark the notification as read
            $notification->markAsRead();
        }
        else
            return null;

        return $notification;
    }
}
