<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'address',
        'washing_time_minutes',
        'drying_time_minutes',
        'wash_buffer_minutes',
        'dry_buffer_minutes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'washing_time_minutes' => 'integer',
            'drying_time_minutes' => 'integer',
            'wash_buffer_minutes' => 'integer',
            'dry_buffer_minutes' => 'integer',
        ];
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function managers()
    {
        return $this->hasMany(Admin::class)->where('role', '!=', Admin::ROLE_SUPER_ADMIN);
    }

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }
}
