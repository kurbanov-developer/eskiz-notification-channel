<?php

namespace Vendor\EskizNotificationChannel;

use Illuminate\Notifications\Notification;

class EskizChannel
{
    protected $client;

    public function __construct(EskizClient $client)
    {
        $this->client = $client;
    }

    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('eskiz')) {
            return;
        }

        $message = $notification->toEskiz($notifiable);

        return $this->client->sendSms($to, $message->content);
    }
}
