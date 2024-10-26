<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $token
    ) {
    }

    public function build()
    {
        $url  = env('FRONTEND_URL', '');
        $link = $url . '/reset-password?token='.$this->token.'&email='.$this->user->email;

        return $this->markdown('mail.forgot-password-mail')
            ->subject('Alteração de senha')
            ->with([
                'resetPasswordLink' => $link,
            ]);
    }
}
