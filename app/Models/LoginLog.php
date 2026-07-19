<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'username',
        'ip_address',
        'user_agent',
        'login_at',
        'logout_at',
        'status',
    ];
}
