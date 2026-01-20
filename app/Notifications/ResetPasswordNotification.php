<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;
    public $url;

    public function __construct($token)
    {
        $this->token = $token;
        Log::info('ResetPasswordNotification created', ['token_preview' => substr($token, 0, 10)]);
    }

    public function via($notifiable)
    {
        Log::info('via() called', ['channels' => ['mail']]);
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        Log::info('toMail() called', ['email' => $notifiable->email, 'url' => $this->url]);
        
        return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $this->url)
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    }
}