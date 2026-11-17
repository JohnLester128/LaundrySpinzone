<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'username',
        'phone',
        'password',
        'branch_id',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN || $this->role === 'staff';
    }

    public function hasRole(array|string $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return in_array($this->role, $roles, true);
    }

    public function getRoleBadgeLabel(): string
    {
        return $this->isSuperAdmin() ? 'Owner' : 'Branch Manager';
    }

    public function getRoleBadgeClasses(): string
    {
        return $this->isSuperAdmin()
            ? 'bg-purple-100/90 text-purple-700 border-purple-200/80 shadow-xs'
            : 'bg-blue-100/90 text-blue-700 border-blue-200/80 shadow-xs';
    }
}
