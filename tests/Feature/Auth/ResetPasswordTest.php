<?php

use App\Models\{PasswordResetTokens, User};
use Illuminate\Support\Facades\{Hash};

it('resets the password successfully with a valid token', function () {
    $user  = User::factory()->create(['password' => 'OldPassword123']);
    $token = PasswordResetTokens::create(['email' => $user->email, 'token' => 'valid-token']);

    $response = $this->putJson(route('password.reset'), [
        'token'                 => $token->token,
        'email'                 => $user->email,
        'password'              => 'NewPassword123',
        'password_confirmation' => 'NewPassword123',
    ]);

    $response->assertStatus(200)
             ->assertJson(['message' => __('Password reset successfully')]);

    // Verify the password has been updated
    $user->refresh();
    expect(Hash::check('NewPassword123', $user->password))->toBeTrue();

    // Ensure the token is deleted
    $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
});

it('fails to reset password with an invalid token', function () {
    $user = User::factory()->create();

    $response = $this->putJson(route('password.reset'), [
        'token'                 => 'invalid-token',
        'email'                 => $user->email,
        'password'              => 'NewPassword123',
        'password_confirmation' => 'NewPassword123',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['token']);
});

it('fails to reset password for a non-existent email', function () {
    $response = $this->putJson(route('password.reset'), [
        'token'                 => 'valid-token',
        'email'                 => 'nonexistent@example.com',
        'password'              => 'NewPassword123',
        'password_confirmation' => 'NewPassword123',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

it('fails to reset password with mismatched password confirmation', function () {
    $user  = User::factory()->create();
    $token = PasswordResetTokens::create(['email' => $user->email, 'token' => 'valid-token']);

    $response = $this->putJson(route('password.reset'), [
        'token'                 => $token->token,
        'email'                 => $user->email,
        'password'              => 'NewPassword123',
        'password_confirmation' => 'DifferentPassword123',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});

it('fails to reset password with missing fields', function () {
    $response = $this->putJson(route('password.reset'), []);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['token', 'email', 'password']);
});

it('fails to reset password with a short password', function () {
    $user  = User::factory()->create();
    $token = PasswordResetTokens::create(['email' => $user->email, 'token' => 'valid-token']);

    $response = $this->putJson(route('password.reset'), [
        'token'                 => $token->token,
        'email'                 => $user->email,
        'password'              => 'short', // Invalid password
        'password_confirmation' => 'short',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});

it('fails to reset password with an invalid email format', function () {
    $user  = User::factory()->create();
    $token = PasswordResetTokens::create(['email' => $user->email, 'token' => 'valid-token']);

    $response = $this->putJson(route('password.reset'), [
        'token'                 => $token->token,
        'email'                 => 'invalid-email', // Invalid email format
        'password'              => 'NewPassword123',
        'password_confirmation' => 'NewPassword123',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});
