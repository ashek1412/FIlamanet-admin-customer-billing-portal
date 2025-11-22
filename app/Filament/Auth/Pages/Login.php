<?php
// app/Filament/Pages/Auth/Login.php

namespace App\Filament\Auth\Pages;
use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class Login extends BaseLogin
{
 //   protected static string $view = 'filament.pages.custom-login';
    use HasCustomLayout;

    public function mount(): void
    {
        parent::mount();

        // Handle flash notifications after page loads
        $this->handleFlashNotifications();
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        $user = \App\Models\User::where('email', $data['email'])->first();

        // Check if user exists
        if (!$user) {
            Log::warning('Failed login attempt - user not found', [
                'email' => $data['email'],
                'ip' => request()->ip(),
            ]);

            throw ValidationException::withMessages([
                'data.email' => 'Invalid login credentials.',
            ]);
        }

        // Check if account is inactive BEFORE attempting authentication
        if (!$user->is_active) {
            Log::warning('Login attempt on inactive account', [
                'email' => $data['email'],
                'ip' => request()->ip(),
            ]);

            throw ValidationException::withMessages([
                'data.email' => 'Your account has been deactivated. Please contact support.',
            ]);
        }

        // Attempt authentication
        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $data['remember'] ?? false)) {
            // Increment failed attempts
            $user->incrementFailedLoginAttempts();
            $user->refresh();

            $remainingAttempts = $user->getRemainingLoginAttempts();

            // Check if account was just deactivated
            if (!$user->is_active) {
                $message = 'Your account has been deactivated due to too many failed login attempts.';
            } elseif ($remainingAttempts <= 3) {
                $message = "Invalid login credentials. {$remainingAttempts} attempts remaining before account deactivation.";
            } else {
                $message = 'Invalid login credentials.';
            }

            Log::warning('Failed login attempt', [
                'email' => $data['email'],
                'ip' => request()->ip(),
                'failed_attempts' => $user->failed_login_attempts,
                'remaining_attempts' => $remainingAttempts,
                'account_active' => $user->is_active,
            ]);

            throw ValidationException::withMessages([
                'data.email' => $message,
            ]);
        }

        // Authentication successful - reset failed login attempts
        $user->resetFailedLoginAttempts();

        Log::info('Successful login', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip(),
        ]);

        return app(LoginResponse::class);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->label('Email Address')
                ->required()
                ->email(),
            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required(),
            Checkbox::make('remember')
                ->label('Remember Me'),
        ];
    }

    protected function handleFlashNotifications(): void
    {



        // Handle password success specifically
        if (session()->has('successpassword')) {

            Notification::make()
                ->title(session('successpassword'))
                ->success() // Changed from danger() to success()
                ->send();
            session()->forget('successpassword');
        }
    }


}
