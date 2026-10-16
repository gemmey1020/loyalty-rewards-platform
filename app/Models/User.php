<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'email_verified_at',
        'remember_token',
        'role',
        'birth_date',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SUPER_ADMIN->value;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN->value;
    }

    public function isUser(): bool
    {
        return $this->role === UserRole::USER->value;
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function pointDeductionHistories()
    {
        return $this->hasMany(PointDeductionHistory::class);
    }

    public function userPoints()
    {
        return $this->hasMany(UserPoint::class);
    }

    public function recoveryToken()
    {
        return $this->hasMany(RecoveryToken::class);
    }
}
