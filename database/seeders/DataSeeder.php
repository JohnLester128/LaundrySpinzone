<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Machine;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\ClothType;
use App\Models\PricingTier;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestStatusHistory;
use App\Models\Sale;
use App\Models\Feedback;
use Carbon\Carbon;

class DataSeeder extends Seeder
{
    public function run(): void
    {
        $panayBranch  = Branch::where('name', 'Panay Branch')->first();
        $baybayBranch = Branch::where('name', 'Baybay Branch')->first();

        $panayManager  = Admin::where('username', 'Panay')->first();
        $baybayManager = Admin::where('username', 'Baybay')->first();

        // ──────────────────────────────────────────────
        //  MACHINES: 3 Washers + 3 Dryers per Branch
        // ──────────────────────────────────────────────
        $machineData = [
            // Panay Branch
            ['branch_id' => $panayBranch->id, 'name' => 'Washer P1', 'brand' => 'Samsung',  'model' => 'WF45R6100AW', 'type' => 'washer', 'status' => 'available'],
            ['branch_id' => $panayBranch->id, 'name' => 'Washer P2', 'brand' => 'LG',       'model' => 'WM3600HWA',   'type' => 'washer', 'status' => 'available'],
            ['branch_id' => $panayBranch->id, 'name' => 'Washer P3', 'brand' => 'Whirlpool', 'model' => 'WTW5000DW',  'type' => 'washer', 'status' => 'available'],
            ['branch_id' => $panayBranch->id, 'name' => 'Dryer P1',  'brand' => 'Samsung',  'model' => 'DVE45R6100W', 'type' => 'dryer',  'status' => 'available'],
            ['branch_id' => $panayBranch->id, 'name' => 'Dryer P2',  'brand' => 'LG',       'model' => 'DLE3600W',    'type' => 'dryer',  'status' => 'available'],
            ['branch_id' => $panayBranch->id, 'name' => 'Dryer P3',  'brand' => 'Whirlpool', 'model' => 'WED5000DW', 'type' => 'dryer',  'status' => 'available'],

            // Baybay Branch
            ['branch_id' => $baybayBranch->id, 'name' => 'Washer B1', 'brand' => 'Samsung',  'model' => 'WF45R6100AW', 'type' => 'washer', 'status' => 'available'],
            ['branch_id' => $baybayBranch->id, 'name' => 'Washer B2', 'brand' => 'LG',       'model' => 'WM3600HWA',   'type' => 'washer', 'status' => 'available'],
            ['branch_id' => $baybayBranch->id, 'name' => 'Washer B3', 'brand' => 'Whirlpool', 'model' => 'WTW5000DW',  'type' => 'washer', 'status' => 'available'],
            ['branch_id' => $baybayBranch->id, 'name' => 'Dryer B1',  'brand' => 'Samsung',  'model' => 'DVE45R6100W', 'type' => 'dryer',  'status' => 'available'],
            ['branch_id' => $baybayBranch->id, 'name' => 'Dryer B2',  'brand' => 'LG',       'model' => 'DLE3600W',    'type' => 'dryer',  'status' => 'available'],
            ['branch_id' => $baybayBranch->id, 'name' => 'Dryer B3',  'brand' => 'Whirlpool', 'model' => 'WED5000DW', 'type' => 'dryer',  'status' => 'available'],
        ];

        foreach ($machineData as $m) {
            Machine::updateOrCreate(
                ['branch_id' => $m['branch_id'], 'name' => $m['name']],
                $m
            );
        }

        // ──────────────────────────────────────────────
        //  SERVICE REQUESTS: 5 per Customer (40 total)
        // ──────────────────────────────────────────────

        // Load cloth types and pricing tiers
        $regularType   = ClothType::where('name', 'Regular Clothes')->first();
        $beddingsType  = ClothType::where('name', 'Beddings, Towels & Curtains')->first();
        $comforterType = ClothType::where('name', 'Comforters')->first();

        $regularTiers   = PricingTier::where('cloth_type_id', $regularType->id)->get();
        $beddingsTier   = PricingTier::where('cloth_type_id', $beddingsType->id)->first();
        $comforterTiers = PricingTier::where('cloth_type_id', $comforterType->id)->get();

        // Status progression (for creating realistic status histories)
        $statusFlow = ['pending', 'received', 'washing', 'drying', 'ready_for_pickup', 'completed'];

        // Define templates for service requests
        $requestTemplates = [
            // completed requests (will also create sales + some feedback)
            ['status' => 'completed',        'days_ago' => 14, 'cloth_idx' => 'regular',   'weight' => 5,  'tier_idx' => 0],
            ['status' => 'completed',        'days_ago' => 10, 'cloth_idx' => 'regular',   'weight' => 8,  'tier_idx' => 1],
            ['status' => 'completed',        'days_ago' => 7,  'cloth_idx' => 'beddings',  'weight' => 4,  'tier_idx' => null],
            // a ready-for-pickup
            ['status' => 'ready_for_pickup', 'days_ago' => 2,  'cloth_idx' => 'comforter', 'weight' => null, 'tier_idx' => 0, 'comforter_size' => 'Single'],
            // a pending one
            ['status' => 'pending',          'days_ago' => 0,  'cloth_idx' => 'regular',   'weight' => 6,  'tier_idx' => 0],
        ];

        $customers = Customer::all();

        $feedbackComments = [
            'Great service, very clean clothes!',
            'Fast turnaround, very satisfied.',
            'Clothes smelled amazing, will come again!',
            'Good quality wash. Staff was friendly.',
            'Excellent folding and packaging.',
            'Quick and reliable. Highly recommended!',
            'The freshness of the clothes was impressive.',
            'Very convenient service overall.',
        ];

        $feedbackAspects = [
            ['Cleanliness & Freshness', 'Fast Turnaround'],
            ['Staff Friendliness', 'Cleanliness & Freshness'],
            ['Fast Turnaround', 'Value for Money'],
            ['Cleanliness & Freshness', 'Staff Friendliness', 'Fast Turnaround'],
        ];

        $feedbackIdx = 0;

        foreach ($customers as $customer) {
            $branchId  = $customer->branch_id;
            $managerId = ($branchId === $panayBranch->id) ? $panayManager->id : $baybayManager->id;

            foreach ($requestTemplates as $tplIdx => $tpl) {
                $createdAt = Carbon::now()->subDays($tpl['days_ago'])->subHours(rand(1, 12));

                // Determine cloth type & pricing
                $clothType = match ($tpl['cloth_idx']) {
                    'regular'   => $regularType,
                    'beddings'  => $beddingsType,
                    'comforter' => $comforterType,
                    default     => $regularType,
                };

                if ($tpl['cloth_idx'] === 'comforter') {
                    $tier = $comforterTiers[$tpl['tier_idx'] ?? 0];
                } elseif ($tpl['cloth_idx'] === 'beddings') {
                    $tier = $beddingsTier;
                } else {
                    $tier = $regularTiers[$tpl['tier_idx'] ?? 0];
                }

                $price = $tier->price;
                $comforterSize = $tpl['comforter_size'] ?? null;
                $weight = $tpl['weight'];
                $quantity = $comforterSize ? 1 : 1;

                $sr = ServiceRequest::create([
                    'customer_id'      => $customer->id,
                    'branch_id'        => $branchId,
                    'admin_id'         => in_array($tpl['status'], ['pending']) ? null : $managerId,
                    'cloth_type_id'    => $clothType->id,
                    'pricing_tier_id'  => $tier->id,
                    'estimated_weight' => $weight,
                    'actual_weight'    => in_array($tpl['status'], ['pending']) ? null : $weight,
                    'comforter_size'   => $comforterSize,
                    'quantity'         => $quantity,
                    'actual_quantity'  => $quantity,
                    'notes'            => null,
                    'estimated_price'  => $price,
                    'final_price'      => in_array($tpl['status'], ['completed', 'ready_for_pickup']) ? $price : null,
                    'status'           => $tpl['status'],
                    'accepted_by'      => in_array($tpl['status'], ['pending']) ? null : $managerId,
                    'weight_verification_status' => in_array($tpl['status'], ['pending']) ? 'pending' : 'verified',
                    'created_at'       => $createdAt,
                    'updated_at'       => $createdAt,
                ]);

                // Create status history entries
                $currentStatusIdx = array_search($tpl['status'], $statusFlow);
                $histTime = $createdAt->copy();

                for ($i = 0; $i <= $currentStatusIdx; $i++) {
                    $startedAt = $histTime->copy();
                    $endedAt   = ($i < $currentStatusIdx) ? $histTime->copy()->addMinutes(rand(20, 60)) : null;

                    ServiceRequestStatusHistory::create([
                        'service_request_id' => $sr->id,
                        'status'             => $statusFlow[$i],
                        'changed_by'         => ($i === 0) ? null : $managerId,
                        'started_at'         => $startedAt,
                        'ended_at'           => $endedAt,
                    ]);

                    if ($endedAt) {
                        $histTime = $endedAt->copy();
                    }
                }

                // Create sales for completed requests
                if ($tpl['status'] === 'completed') {
                    Sale::create([
                        'service_request_id' => $sr->id,
                        'branch_id'          => $branchId,
                        'amount'             => $price,
                        'sold_at'            => $histTime,
                    ]);

                    // Create feedback for some completed requests (first 2 per customer)
                    if ($tplIdx < 2) {
                        Feedback::create([
                            'customer_id'        => $customer->id,
                            'service_request_id' => $sr->id,
                            'branch_id'          => $branchId,
                            'nature'             => 'feedback',
                            'is_anonymous'       => false,
                            'type'               => 'service',
                            'rating'             => rand(4, 5),
                            'subject'            => null,
                            'comment'            => $feedbackComments[$feedbackIdx % count($feedbackComments)],
                            'aspects'            => $feedbackAspects[$feedbackIdx % count($feedbackAspects)],
                        ]);
                        $feedbackIdx++;
                    }
                }
            }
        }
    }
}
