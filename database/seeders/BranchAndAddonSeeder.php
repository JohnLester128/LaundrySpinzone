<?php
namespace Database\Seeders;
use App\Models\Addon;
use App\Models\Branch;
use Illuminate\Database\Seeder;
class BranchAndAddonSeeder extends Seeder
{
    public function run(): void
    {
        Branch::updateOrCreate(['name' => 'Baybay Branch'], ['address' => 'Baybay', 'is_active' => true]);
        Branch::updateOrCreate(['name' => 'Panay Branch'], ['address' => 'Panay', 'is_active' => true]);
        foreach ([['Detergent', 10], ['Fabric Conditioner (FabCon)', 10], ['Color Safe', 10]] as [$name, $price]) Addon::updateOrCreate(['name' => $name], ['price' => $price, 'is_active' => true]);
    }
}
