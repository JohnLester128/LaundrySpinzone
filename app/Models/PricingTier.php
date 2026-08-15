<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'cloth_type_id', 'name', 'min_weight', 'max_weight', 'size', 'price', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_weight' => 'decimal:2', 'max_weight' => 'decimal:2',
            'price' => 'decimal:2', 'is_active' => 'boolean',
        ];
    }

    public function clothType()
    {
        return $this->belongsTo(ClothType::class);
    }
}
