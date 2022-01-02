<?php

namespace App\Notifications;

use App\Notifications\Channels\DatabaseChannel;
use App\Cms\Notification;

class EmailVerified extends Notification
{
	public function via($notifiable)
    {
        return [
            DatabaseChannel::class,
            'mail',
        ];
    }
}
