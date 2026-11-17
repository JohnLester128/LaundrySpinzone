<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    public const SECURITY_QUESTIONS = [
        'What is the name of your first pet?',
        'What is your mother\'s maiden name?',
        'What was the name of your elementary school?',
        'What city were you born in?',
        'What is your favorite childhood food?',
    ];

    protected $fillable = [
        'username',
        'phone',
        'password',
        'security_question',
        'security_answer',
        'branch_id',
    ];

    protected $hidden = [
        'password',
        'security_answer',
        'remember_token',
    ];

    public static function normalizeSecurityAnswer(string $answer): string
    {
        return mb_strtolower(trim($answer));
    }

    public function branch() { return $this->belongsTo(Branch::class); }
    public function serviceRequests() { return $this->hasMany(ServiceRequest::class); }
    public function feedbacks() { return $this->hasMany(Feedback::class); }
}
