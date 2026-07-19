<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'admin_id',
        'cloth_type_id',
        'pricing_tier_id',
        'estimated_weight',
        'actual_weight',
        'comforter_size',
        'quantity',
        'notes',
        'estimated_price',
        'final_price',
        'status',
        'accepted_by',
    ];

    protected function casts(): array
    {
        return [
            'estimated_weight' => 'decimal:2',
            'actual_weight' => 'decimal:2',
            'estimated_price' => 'decimal:2',
            'final_price' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function acceptedBy()
    {
        return $this->belongsTo(Admin::class, 'accepted_by');
    }
}
