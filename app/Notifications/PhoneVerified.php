<?php

namespace App\Notifications;

use App\Notifications\Channels\DatabaseChannel;
use App\Notifications\Channels\SmsChannel;
use App\Cms\Notification;

class PhoneVerified extends Notification
{
	public function via($notifiable)
    {
        return [
            DatabaseChannel::class,
            SmsChannel::class,
        ];
    }
}
