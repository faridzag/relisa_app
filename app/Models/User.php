<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    //use TwoFactorAuthenticatable;

    const ROLE_ADMIN = 'ADMIN';
    const ROLE_EVENTMANAGER = 'EVENT-MANAGER';
    const ROLE_USER = 'VOLUNTEER';
    const ROLE_DEFAULT = self::ROLE_USER;

    const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_EVENTMANAGER => 'Event Manager',
        self::ROLE_USER => 'Volunteer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'telp',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'app' && $this->can('viewAppPanel', User::class)) {
            return true;
        } elseif ($panel->getId() === 'admin' && $this->can('viewAdminPanel', User::class)) {
            return true;
        }
        return false;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isEventManager(): bool
    {
        return $this->role === self::ROLE_EVENTMANAGER;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function isAccepted(): bool
    {
        return $this->registrations()->status == 'accepted';
    }
}
