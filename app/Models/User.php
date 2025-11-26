<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;



class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable, AuthenticationLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_agreed',
        'is_admin',
        'customer_id',
        'view_dms',
        'view_dws',
        'is_active',
        'must_reset_password',
        'password_changed_at',
        'is_first_login',
        'failed_login_attempts'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_changed_at' => 'datetime',
            'must_reset_password' => 'boolean',
            'is_first_login' => 'boolean',
        ];
    }

    /**
     * Determine if the user can access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        //dd($this);
        return true;
    }

    /**
     * Send the password reset notification with Filament panel URL.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $url = \Filament\Facades\Filament::getPanel('app')
            ->getPasswordResetUrl($token, $this);

        $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($url));
    }

    /**
     * The posts that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customer()
    {
        return $this->belongsTo(CustomerList::class, 'customer_id', 'id');
    }

    public function needsPasswordReset(): bool
    {
        // Check if first login
        if ($this->is_admin) {
            return false;
        }

        if ($this->is_first_login) {
            return true;
        }

        // Check if password must be reset flag is set
        if ($this->must_reset_password) {
            return true;
        }

        // Check if password is older than 90 days
        if ($this->password_changed_at) {
            return Carbon::parse($this->password_changed_at)->addDays(intval(config('app.password_validity')))->isPast();
        }

        return true; // If no password_changed_at is set, require reset
    }

    public function markPasswordAsChanged(): void
    {
        $this->update([
            'password_changed_at' => now(),
            'must_reset_password' => false,
            'is_first_login' => false,
        ]);
    }
    /**
     * Increment failed login attempts and deactivate if limit reached
     */
    public function incrementFailedLoginAttempts(): void
    {
        $this->increment('failed_login_attempts');

        // Deactivate user after 10 failed attempts
        if ($this->failed_login_attempts >= 10) {
            $this->update(['is_active' => false]);
        }
    }

    /**
     * Get remaining login attempts before deactivation
     */
    public function getRemainingLoginAttempts(): int
    {
        return max(0, 10 - $this->failed_login_attempts);
    }

    /**
     * Reactivate user and reset failed attempts
     */
    public function reactivateUser(): void
    {
        $this->update([
            'is_active' => true,
            'failed_login_attempts' => 0,
        ]);
    }

    /**
     * Reset failed login attempts on successful login
     */
    public function resetFailedLoginAttempts(): void
    {
        $this->update(['failed_login_attempts' => 0]);
    }
}
