<?php

use App\Events\ForgotPassword;
use App\Models\User;
use Illuminate\Support\Facades\Event;

it('creates the ForgotPassword event with the correct user and token', function () {
    $user  = User::factory()->create();
    $token = 'sample-token';

    $event = new ForgotPassword($user, $token);

    expect($event->user)->toBe($user)
        ->and($event->token)->toBe($token);
});

it('dispatches the ForgotPassword event', function () {
    Event::fake();

    $user  = User::factory()->create();
    $token = 'sample-token';

    ForgotPassword::dispatch($user, $token);

    Event::assertDispatched(ForgotPassword::class, function ($event) use ($user, $token) {
        return $event->user->is($user) && $event->token === $token;
    });
});
