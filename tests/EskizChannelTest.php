<?php

namespace KurbanovDeveloper\EskizNotificationChannel\Tests;

use PHPUnit\Framework\TestCase;
use KurbanovDeveloper\EskizNotificationChannel\EskizChannel;
use KurbanovDeveloper\EskizNotificationChannel\EskizMessage;
use GuzzleHttp\Client;

class EskizChannelTest extends TestCase
{
    public function test_it_can_send_sms()
    {
        $client = $this->createMock(Client::class);
        $channel = new EskizChannel($client);

        $notifiable = new class {
            public function routeNotificationForEskiz() { return '998900000000'; }
        };

        $notification = new class {
            public function toEskiz($notifiable) { return new EskizMessage('Test message'); }
        };

        $result = $channel->send($notifiable, $notification);

        $this->assertIsArray($result);
    }
}
