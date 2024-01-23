<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class SmsChannel
{

    public function send(object $notifiable, Notification $notification):void
    {
        $notification->toSMS($notifiable);
    }
}
