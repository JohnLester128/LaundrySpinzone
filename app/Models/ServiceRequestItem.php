<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'item_name',
        'is_custom',
        'declared_qty',
        'verified_qty',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_custom' => 'boolean',
            'declared_qty' => 'integer',
            'verified_qty' => 'integer',
        ];
    }

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Effective quantity (verified if present, otherwise declared)
     */
    public function getEffectiveQtyAttribute(): int
    {
        return $this->verified_qty ?? $this->declared_qty;
    }

    /**
     * Check if verified count matches declared count
     */
    public function isVerifiedMatch(): bool
    {
        if ($this->verified_qty === null) {
            return false;
        }
        return $this->verified_qty === $this->declared_qty;
    }
}
