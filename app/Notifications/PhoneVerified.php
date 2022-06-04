<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Cms\Notification;
use App\Notifications\Channels\{DatabaseChannel, SmsChannel};

final class PhoneVerified extends Notification
{
    public function via($notifiable)
    {
        return [DatabaseChannel::class, SmsChannel::class];
    }
}
