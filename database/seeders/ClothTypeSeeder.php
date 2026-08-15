<?php

namespace Database\Seeders;

use App\Models\ClothType;
use App\Models\PricingTier;
use Illuminate\Database\Seeder;

class ClothTypeSeeder extends Seeder
{
    public function run(): void
    {
        $regular = ClothType::updateOrCreate(['name' => 'Regular Clothes'], ['requires_size' => false]);
        $beddings = ClothType::updateOrCreate(['name' => 'Beddings, Towels & Curtains'], ['requires_size' => false]);
        $comforter = ClothType::updateOrCreate(['name' => 'Comforters'], ['requires_size' => true]);

        $tiers = [
            [$regular, '1-6 kg', 1, 6, null, 95], [$regular, '7-8 kg', 7, 8, null, 120],
            [$regular, '9 kg', 9, 9, null, 145], [$beddings, '1-5 kg', 1, 5, null, 145],
            [$comforter, 'Single Size (1 pc)', null, null, 'Single', 100],
            [$comforter, 'Double Size (1 pc)', null, null, 'Double', 130],
            [$comforter, 'Queen Size (1 pc)', null, null, 'Queen', 150],
            [$comforter, 'King Size (1 pc)', null, null, 'King', 170],
        ];

        foreach ($tiers as [$type, $name, $min, $max, $size, $price]) {
            PricingTier::updateOrCreate(
                ['cloth_type_id' => $type->id, 'name' => $name],
                ['min_weight' => $min, 'max_weight' => $max, 'size' => $size, 'price' => $price, 'is_active' => true]
            );
        }
    }
}
