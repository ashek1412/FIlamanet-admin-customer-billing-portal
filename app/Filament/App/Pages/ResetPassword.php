<?php

namespace App\Filament\App\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetPassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static string $view = 'filament.app.pages.reset-password';
    protected static ?string $title = 'Reset Your Password';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $layout = 'filament-panels::components.layout.simple';
    protected static ?string $slug = 'reset-password';

    public ?array $data = [];

    public function mount(): void
    {
        // Check if user needs password reset
        if (!Auth::check()) {
            $this->redirectToLogin();
            return;
        }

        if (!Auth::user()->needsPasswordReset()) {
            $this->redirectToDashboard();
            return;
        }
    }

    protected function redirectToLogin(): void
    {
        $panel = Filament::getCurrentPanel();
        $panelId = $panel ? $panel->getId() : 'admin';
        redirect()->route("filament.{$panelId}.auth.login");
    }

    protected function redirectToDashboard(): void
    {
        $panel = Filament::getCurrentPanel();
        $panelId = $panel ? $panel->getId() : 'admin';

        // Try to redirect to dashboard
        try {
            if (app('router')->has("filament.{$panelId}.pages.dashboard")) {
                $this->redirect(route("filament.{$panelId}.pages.dashboard"));
                return;
            }
        } catch (\Exception $e) {
            // If dashboard route doesn't exist, try the panel home
        }

        // Fallback redirects
        if (app('router')->has("filament.{$panelId}.pages.index")) {
            $this->redirect(route("filament.{$panelId}.pages.index"));
            return;
        }

        // Last resort - redirect to panel root
        $this->redirect("/{$panelId}");
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->heading('Password Reset Required')
                    ->description($this->getResetReason())
                    ->schema([
                        TextInput::make('currentPassword')
                            ->label('Current Password')
                            ->password()
                            ->required()
                            ->revealable()
                            ->rule(function () {
                                return function ($attribute, $value, $fail) {
                                    if (!Hash::check($value, Auth::user()->password)) {
                                        $fail('The current password is incorrect.');
                                    }
                                };
                            }),

                        TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->same('passwordConfirmation')
                            ->revealable()
                            ->rules([

                                Password::min(8)
                                    ->mixedCase()
                                    ->numbers()
                                    ->symbols()
                                    ->uncompromised(),
                            ])
                            ->rule(function () {
                                return function ($attribute, $value, $fail) {
                                    $currentPassword = data_get($this->data, 'currentPassword');
                                    if ($currentPassword && $value === $currentPassword) {
                                        $fail('The new password must be different from your current password.');
                                    }
                                };
                            })
                            ->helperText('Must be at least 8 characters with mixed case, numbers, and symbols.'),

                        TextInput::make('passwordConfirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->required()
                            ->revealable(),
                    ])
            ])
            ->statePath('data');
    }

    protected function getResetReason(): string
    {
        $user = Auth::user();

        if ($user->is_first_login) {
            return 'Welcome! This is your first login. Please set a secure password.';
        }



        if ($user->password_changed_at && $user->password_changed_at->addDays(intval(config('app.password_validity')))->isPast()) {
            $daysSince = intval($user->password_changed_at->diffInDays(now()));
            return "Your password expired after {$daysSince} days. Please set a new password.";
        }

        return 'You are required to reset your password for security reasons.';
    }

    public function submit(): void
    {
        // Validate the form first
        $data = $this->form->getState();

        try {
            $user = Auth::user();

            // Validate current password (this should already be validated by form rules, but double-check)
            if (!Hash::check($data['currentPassword'], $user->password)) {
                Notification::make()
                    ->title('Current password is incorrect')
                    ->danger()
                    ->send();
                return;
            }

            // Check if new password is different from current password
            if ($data['currentPassword'] === $data['password']) {
                Notification::make()
                    ->title('Password unchanged')
                    ->body('The new password must be different from your current password.')
                    ->danger()
                    ->send();
                return;
            }

            // Update password using the form data
            $user->password = Hash::make($data['password']);
            $user->markPasswordAsChanged();
            $user->save(); // Make sure to save the changes



            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            $this->data = [];
            request()->session()->regenerate();


            session()->flash('successpassword', 'Password updated successfully! Please log in with your new password.');
            $panel = Filament::getCurrentPanel();
            $panelId = $panel ? $panel->getId() : 'admin';
            $this->redirect(route("filament.{$panelId}.auth.login"));


            // Clear the form data


            // Redirect to dashboard
         //   $this->redirectToDashboard();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error updating password')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
