<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;
use Illuminate\Support\Facades\Password;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\CanResetPassword;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Support\Facades\Log;

class RequestPasswordReset extends BaseRequestPasswordReset
{
    public function request(): void
    {
        Log::info('Password reset requested');
        
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();
            return;
        }

        $data = $this->form->getState();
        
        Log::info('Form data', $data);

        $status = Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
            $data,
            function (CanResetPassword $user, string $token): void {
                Log::info('Inside sendResetLink callback', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'token' => $token
                ]);
                
                if (! method_exists($user, 'notify')) {
                    Log::error('User model does not have notify method');
               throw new \Exception("Model [" . get_class($user) . "] does not have a [notify()] method.");
                }

                $notification = new \Filament\Notifications\Auth\ResetPassword($token);
                $notification->url = Filament::getResetPasswordUrl($token, $user);
                
                Log::info('About to send notification', [
                    'notification_class' => get_class($notification),
                    'url' => $notification->url
                ]);
                
                $user->notify($notification);
                
                Log::info('Notification sent');
            },
        );

        Log::info('Password reset status', ['status' => $status]);

        if ($status !== Password::RESET_LINK_SENT) {
            Notification::make()
                ->title(__($status))
                ->danger()
                ->send();
            return;
        }

        Notification::make()
            ->title(__($status))
            ->success()
            ->send();

        $this->form->fill();
    }
}