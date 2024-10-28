<?php

use App\Events\{OrderCreatedEvent};
use App\Listeners\{OrderCreatedListener};
use App\Models\{Client, Order};
use App\Models\Product;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ClientOrderNotification;

it('sends notification when OrderProductsSynced event is triggered', function () {
    Notification::fake();

    $client = Client::factory()->create();
    Product::factory()->create();
    $order = Order::factory()->create(['client_id' => $client->id]);

    $listener = new OrderCreatedListener();
    $listener->handle(new OrderCreatedEvent($order));

    Notification::assertSentTo($client, ClientOrderNotification::class);
});
