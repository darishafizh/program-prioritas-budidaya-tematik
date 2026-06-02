<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasHashids;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasHashids;

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getNameAttribute(): string
    {
        return $this->username;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVerifikator(): bool
    {
        return $this->role === 'verifikator';
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'verifikator' => 'Verifikator',
            default => 'User',
        };
    }
}
