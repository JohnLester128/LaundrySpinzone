<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'branch_id',
        'admin_id',
        'cloth_type_id',
        'pricing_tier_id',
        'estimated_weight',
        'actual_weight',
        'comforter_size',
        'quantity',
        'actual_quantity',
        'notes',
        'estimated_price',
        'final_price',
        'status',
        'accepted_by',
        'washing_machine_id',
        'dryer_machine_id',
    ];

    protected function casts(): array
    {
        return [
            'estimated_weight' => 'decimal:2',
            'actual_weight' => 'decimal:2',
            'estimated_price' => 'decimal:2',
            'final_price' => 'decimal:2',
            'quantity' => 'integer',
            'actual_quantity' => 'integer',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function clothType() { return $this->belongsTo(ClothType::class); }
    public function pricingTier() { return $this->belongsTo(PricingTier::class); }
    public function addons() { return $this->belongsToMany(Addon::class, 'service_request_addons')->withPivot('price')->withTimestamps(); }
    public function branch() { return $this->belongsTo(Branch::class); }
    public function washingMachine() { return $this->belongsTo(Machine::class, 'washing_machine_id'); }
    public function dryerMachine() { return $this->belongsTo(Machine::class, 'dryer_machine_id'); }
    public function statusHistories() { return $this->hasMany(ServiceRequestStatusHistory::class); }
    public function sale() { return $this->hasOne(Sale::class); }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function acceptedBy()
    {
        return $this->belongsTo(Admin::class, 'accepted_by');
    }
}
