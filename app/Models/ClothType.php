<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClothType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'requires_size', 'is_active'];

    protected function casts(): array
    {
        return ['requires_size' => 'boolean', 'is_active' => 'boolean'];
    }

    public function pricingTiers()
    {
        return $this->hasMany(PricingTier::class);
    }
}
