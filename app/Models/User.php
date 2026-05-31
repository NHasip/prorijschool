<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_BEHEERDER = 'beheerder';
    public const ROLE_INSTRUCTEUR = 'instructeur';
    public const ROLE_LEERLING = 'leerling';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'approval_status',
        'approved_at',
        'two_factor_enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
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
            'approved_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'two_factor_expires_at' => 'datetime',
        ];
    }

    public function isRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isApproved(): bool
    {
        if (! $this->isRole(self::ROLE_LEERLING)) {
            return true;
        }

        return $this->approval_status === 'approved';
    }

    public function requiresTwoFactor(): bool
    {
        if (! config('auth.portal.two_factor_enabled', false)) {
            return false;
        }

        if ($this->isRole(self::ROLE_ADMIN, self::ROLE_INSTRUCTEUR)) {
            return true;
        }

        return $this->isRole(self::ROLE_LEERLING) && $this->two_factor_enabled;
    }

    public function generateTwoFactorCode(): string
    {
        $code = (string) random_int(100000, 999999);
        $this->forceFill([
            'two_factor_code' => Hash::make($code),
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        return $code;
    }

    public function clearTwoFactorCode(): void
    {
        $this->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function instructorProfile(): HasOne
    {
        return $this->hasOne(Instructor::class);
    }
}
