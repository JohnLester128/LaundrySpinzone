<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CycleTime extends Model
{
    protected $fillable = [
        'kg',
        'washing_time_minutes',
        'drying_time_minutes',
    ];

    protected function casts(): array
    {
        return [
            'kg' => 'integer',
            'washing_time_minutes' => 'integer',
            'drying_time_minutes' => 'integer',
        ];
    }
}
