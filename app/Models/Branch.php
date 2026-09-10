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
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'washing_time_minutes' => 'integer',
            'drying_time_minutes' => 'integer',
        ];
    }
}
