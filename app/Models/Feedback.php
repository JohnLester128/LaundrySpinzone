<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'customer_id',
        'service_request_id',
        'branch_id',
        'nature',
        'status',
        'is_anonymous',
        'type',
        'rating',
        'subject',
        'comment',
        'aspects',
        'resolved_at',
        'resolved_by',
        'resolution_note',
    ];

    public function isComplaint(): bool
    {
        return $this->nature === 'complaint';
    }

    public function isFeedback(): bool
    {
        return $this->nature !== 'complaint';
    }

    public function isAnonymous(): bool
    {
        return (bool)$this->is_anonymous;
    }

    public function isOpen(): bool
    {
        return empty($this->status) || $this->status === 'open';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
            default => 'Open',
        };
    }

    protected $casts = [
        'is_anonymous' => 'boolean',
        'rating' => 'integer',
        'aspects' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function resolvedBy()
    {
        return $this->belongsTo(Admin::class, 'resolved_by');
    }

    public function replies()
    {
        return $this->hasMany(FeedbackReply::class)->orderBy('created_at', 'asc');
    }
}
