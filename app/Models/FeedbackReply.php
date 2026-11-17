<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackReply extends Model
{
    use HasFactory;

    protected $table = 'feedback_replies';

    protected $fillable = [
        'feedback_id',
        'sender_type',
        'sender_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'sender_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'sender_id');
    }

    public function isAdmin(): bool
    {
        return $this->sender_type === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->sender_type === 'customer';
    }

    public function getSenderDisplayNameAttribute(): string
    {
        if ($this->isAdmin()) {
            return $this->admin?->username ? ucfirst($this->admin->username) . ' (Staff)' : 'Management';
        }

        if ($this->feedback && $this->feedback->isAnonymous()) {
            return 'Customer (Anonymous)';
        }

        return $this->customer?->username ? ucfirst($this->customer->username) : 'Customer';
    }
}
