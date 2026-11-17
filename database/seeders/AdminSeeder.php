<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Admin;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Owner (Super Admin) — not tied to any branch
        Admin::updateOrCreate(
            ['username' => 'Owner'],
            [
                'phone'     => '09123456789',
                'password'  => Hash::make('password'),
                'branch_id' => null,
                'role'      => Admin::ROLE_SUPER_ADMIN,
            ]
        );

        // 2. Manager for Panay Branch
        $panayBranch = Branch::where('name', 'Panay Branch')->first();
        Admin::updateOrCreate(
            ['username' => 'Panay'],
            [
                'phone'     => '09111111111',
                'password'  => Hash::make('password'),
                'branch_id' => $panayBranch?->id,
                'role'      => Admin::ROLE_ADMIN,
            ]
        );

        // 3. Manager for Baybay Branch
        $baybayBranch = Branch::where('name', 'Baybay Branch')->first();
        Admin::updateOrCreate(
            ['username' => 'Baybay'],
            [
                'phone'     => '09222222222',
                'password'  => Hash::make('password'),
                'branch_id' => $baybayBranch?->id,
                'role'      => Admin::ROLE_ADMIN,
            ]
        );
    }
}
