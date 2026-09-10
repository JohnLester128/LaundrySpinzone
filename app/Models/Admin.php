<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory;

    protected $fillable = [
        'username',
        'phone',
        'password',
        'branch_id', 'role',
    ];
    public function branch() { return $this->belongsTo(Branch::class); }
}
