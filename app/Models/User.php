<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Check if user is the shop owner
     */
    public function isOwner(): bool
    {
        return $this->shop !== null;
    }

    /**
     * Get user's role (owner, manager, operator, viewer)
     */
    public function getRole(): string
    {
        if ($this->isOwner()) {
            return 'owner';
        }
        return $this->employee?->role ?? 'viewer';
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string ...$roles): bool
    {
        return in_array($this->getRole(), $roles);
    }
}
