<?php

namespace App\Notifications;

use App\Packages\SMS\SMS;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SwapNotification extends Notification
{
    use Queueable;
    public function __construct(
        private string $message
    )
    {
        $this->queue = 'swap-notification';
    }

    public function via($notifiable): array
    {
        return [SmsChannel::class];
    }

    public function toSMS(object $notifiable): void
    {
        SMS::sendSMS($this->message, $notifiable->mobile_number);
    }
}
