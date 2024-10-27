<?php

namespace App\Listeners;

use App\Events\{OrderCreatedEvent};
use App\Notifications\ClientOrderNotification;

class OrderCreatedListener
{
    public function handle(OrderCreatedEvent $event): void
    {
        $client = $event->order->client;
        $client->notify(new ClientOrderNotification($event->order));
    }
}
