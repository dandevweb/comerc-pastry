<?php

use App\Models\{Client, Order};
use App\Models\Product;
use App\Notifications\ClientOrderNotification;
use Illuminate\Support\Facades\Notification;

it('sends a notification to the client when an order is created', function () {
    Notification::fake();

    $client = Client::factory()->create();
    Product::factory()->create();
    $order = Order::factory()->create(['client_id' => $client->id]);

    Notification::send($client, new ClientOrderNotification($order));

    Notification::assertSentTo($client, ClientOrderNotification::class, function ($notification, $channels) use ($order) {
        return $notification->order->id === $order->id && $channels === ['mail'];
    });
});
