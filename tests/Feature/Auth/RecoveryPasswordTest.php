<?php

use App\Models\{PasswordResetTokens, User};
use App\Mail\ForgotPasswordMail;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas, postJson};

it('should be able to request a password recovery sending notification to the user', function () {
    Mail::fake();

    $user = User::factory()->create();

    postJson(route('password.recovery'), [
        'email' => $user->email,
    ])->assertSuccessful();

    assertDatabaseHas(PasswordResetTokens::class, [
        'email' => $user->email,
    ]);

    Mail::assertSent(ForgotPasswordMail::class, 1);

    Mail::assertSent(ForgotPasswordMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('validates email property', function ($value, $rule) {
    postJson(route('password.recovery'), [
        'email' => $value,
    ])->assertJsonMissingValidationErrors([
        'email' => $rule,
    ]);
})->with([
    'required' => ['', 'required'],
    'exists'   => ['teste@gmail.com', 'exists:users,email'],
]);

it('needs to create a token when requesting for a password recovery', function () {
    $user = User::factory()->create();

    postJson(route('password.recovery'), [
        'email' => $user->email,
    ])->assertSuccessful();

    assertDatabaseCount('password_reset_tokens', 1);
    assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
});

it('fails to request password recovery with an unregistered email', function () {
    $response = postJson(route('password.recovery'), [
        'email' => 'unregistered@example.com',
    ]);

    $response->assertStatus(422);
});

it('fails to request password recovery with an empty email', function () {
    $response = postJson(route('password.recovery'), [
        'email' => '',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

it('fails to request password recovery with an invalid email format', function () {
    $response = postJson(route('password.recovery'), [
        'email' => 'invalid-email',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});
