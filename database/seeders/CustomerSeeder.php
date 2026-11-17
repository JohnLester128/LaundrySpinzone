<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Customer;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $panayBranch  = Branch::where('name', 'Panay Branch')->first();
        $baybayBranch = Branch::where('name', 'Baybay Branch')->first();

        $customers = [
            // 4 customers for Panay Branch
            ['username' => 'Juan',    'phone' => '09301000001', 'branch_id' => $panayBranch?->id],
            ['username' => 'Maria',   'phone' => '09301000002', 'branch_id' => $panayBranch?->id],
            ['username' => 'Pedro',   'phone' => '09301000003', 'branch_id' => $panayBranch?->id],
            ['username' => 'Rosa',    'phone' => '09301000004', 'branch_id' => $panayBranch?->id],

            // 4 customers for Baybay Branch
            ['username' => 'Carlos',  'phone' => '09302000001', 'branch_id' => $baybayBranch?->id],
            ['username' => 'Elena',   'phone' => '09302000002', 'branch_id' => $baybayBranch?->id],
            ['username' => 'Miguel',  'phone' => '09302000003', 'branch_id' => $baybayBranch?->id],
            ['username' => 'Sofia',   'phone' => '09302000004', 'branch_id' => $baybayBranch?->id],
        ];

        foreach ($customers as $data) {
            Customer::updateOrCreate(
                ['username' => $data['username']],
                [
                    'phone'     => $data['phone'],
                    'password'  => Hash::make('password'),
                    'branch_id' => $data['branch_id'],
                ]
            );
        }
    }
}
