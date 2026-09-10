<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\ServiceRequest;
use App\Models\Machine;
use App\Models\Sale;
use App\Models\PricingTier;
use App\Models\ClothType;
use App\Models\Addon;
use App\Models\CycleTime;
use App\Models\ServiceRequestStatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function showRegister()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:admins,username',
            'password' => 'required|min:6',
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.login')->with('success', 'Registered! Please login.');
    }

    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();

            LoginLog::create([
                'user_id' => $admin->id,
                'user_type' => 'admin',
                'username' => $admin->username,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
                'status' => 'success',
            ]);

            return redirect()->route('admin.dashboard');
        }

        LoginLog::create([
            'user_id' => 0,
            'user_type' => 'admin',
            'username' => $request->username,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => now(),
            'status' => 'failed',
        ]);

        return back()
            ->withInput($request->only('username'))
            ->withErrors(['username' => 'Invalid admin credentials']);
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin) {
            $log = LoginLog::where('user_id', $admin->id)
                ->where('user_type', 'admin')
                ->whereNull('logout_at')
                ->latest()
                ->first();

            if ($log) {
                $log->logout_at = now();
                $log->save();
            }
        }

        Auth::guard('admin')->logout();

        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        $branchId = $admin->branch_id ?? null;

        $requests = ServiceRequest::when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->with(['customer', 'clothType', 'branch', 'washingMachine', 'dryerMachine'])
            ->latest('id')
            ->take(6)
            ->get();

        $requestCount = ServiceRequest::when($branchId, fn ($q) => $q->where('branch_id', $branchId))->count();
        $pendingCount = ServiceRequest::when($branchId, fn ($q) => $q->where('branch_id', $branchId))->where('status', 'received')->count();
        $activeCount = ServiceRequest::when($branchId, fn ($q) => $q->where('branch_id', $branchId))->whereIn('status', ['received', 'washing', 'drying', 'ready_for_pickup', 'ready'])->count();
        $salesTotal = Sale::when($branchId, fn ($q) => $q->where('branch_id', $branchId))->sum('amount');

        return view('admin.dashboard', [
            'requestCount' => $requestCount,
            'pendingCount' => $pendingCount,
            'activeCount' => $activeCount,
            'salesTotal' => $salesTotal,
            'requests' => $requests,
        ]);
    }

    public function loginLogs()
    {
        $filter = request('filter', 'all');
        $sort = request('sort', 'newest');

        $query = LoginLog::query();

        if ($filter === 'success') {
            $query->where('status', 'success');
        } elseif ($filter === 'failed') {
            $query->where('status', 'failed');
        } elseif ($filter === 'active') {
            $query->where('status', 'success')->whereNull('logout_at');
        }

        $logs = match ($sort) {
            'oldest' => $query->orderBy('id', 'asc')->get(),
            'user_asc' => $query->orderBy('username', 'asc')->get(),
            'user_desc' => $query->orderBy('username', 'desc')->get(),
            'type_admin' => $query->orderByRaw("user_type = 'admin' DESC")->orderBy('id', 'desc')->get(),
            'type_customer' => $query->orderByRaw("user_type = 'customer' DESC")->orderBy('id', 'desc')->get(),
            'ip_asc' => $query->orderBy('ip_address', 'asc')->get(),
            'ip_desc' => $query->orderBy('ip_address', 'desc')->get(),
            'status_asc' => $query->orderBy('status', 'asc')->get(),
            'status_desc' => $query->orderBy('status', 'desc')->get(),
            default => $query->orderBy('id', 'desc')->get(),
        };

        return view('admin.login-logs', ['logs' => $logs, 'filter' => $filter, 'sort' => $sort]);
    }

    public function customers()
    {
        $sort = request('sort', 'newest');
        $query = Customer::query();

        $customers = match ($sort) {
            'oldest' => $query->orderBy('id', 'asc')->get(),
            'name_asc' => $query->orderBy('username', 'asc')->get(),
            'name_desc' => $query->orderBy('username', 'desc')->get(),
            'id_asc' => $query->orderBy('id', 'asc')->get(),
            'id_desc' => $query->orderBy('id', 'desc')->get(),
            default => $query->orderBy('id', 'desc')->get(),
        };

        return view('admin.customers', ['customers' => $customers, 'sort' => $sort]);
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->password = Hash::make($request->password);
        $customer->save();

        return redirect()->route('admin.customers')->with('success', 'Password reset successfully for ' . $customer->username);
    }

    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        $branch = $admin->branch;

        return view('admin.profile', compact('branch'));
    }

    public function settings()
    {
        $admin = Auth::guard('admin')->user();
        $branch = $admin->branch;
        $clothTypes = ClothType::with('pricingTiers')->get();
        $addons = Addon::all();
        $cycleTimes = CycleTime::orderBy('kg', 'asc')->get();

        return view('admin.settings', compact('branch', 'clothTypes', 'addons', 'cycleTimes'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $data = $request->validate([
            'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,
            'phone' => 'nullable|string|max:50',
            'branch_name' => 'required|string|max:255',
            'branch_address' => 'required|string|max:255',
        ]);
        $admin->update([
            'username' => $data['username'],
            'phone' => $data['phone'] ?? null,
        ]);
        $branch = $admin->branch;
        if (!$branch) {
            $branch = Branch::create(['name' => $data['branch_name'], 'address' => $data['branch_address'], 'is_active' => true]);
            $admin->update(['branch_id' => $branch->id]);
        } else {
            $branch->update(['name' => $data['branch_name'], 'address' => $data['branch_address']]);
        }
        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return back()->with('success', 'Password updated successfully!');
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'cycle_times' => 'nullable|array',
            'cycle_times.*.washing_time_minutes' => 'required|integer|min:1|max:300',
            'cycle_times.*.drying_time_minutes' => 'required|integer|min:1|max:300',
            'tiers' => 'nullable|array',
            'tiers.*.price' => 'required|numeric|min:0',
            'addons' => 'nullable|array',
            'addons.*.price' => 'required|numeric|min:0',
        ]);

        // Update weight-based cycle times (1kg - 8kg)
        if (!empty($data['cycle_times'])) {
            foreach ($data['cycle_times'] as $id => $cycleData) {
                CycleTime::where('id', $id)->update([
                    'washing_time_minutes' => $cycleData['washing_time_minutes'],
                    'drying_time_minutes' => $cycleData['drying_time_minutes'],
                ]);
            }
        }

        // Update pricing tiers
        $tiersInput = $request->input('tiers', []);
        $allTiers = PricingTier::all();
        foreach ($allTiers as $tier) {
            if (isset($tiersInput[$tier->id])) {
                $tierData = $tiersInput[$tier->id];
                $isActive = isset($tierData['is_active']) ? ($tierData['is_active'] == '1' || $tierData['is_active'] == 'on' || $tierData['is_active'] === true) : true;
                $tier->update([
                    'price' => $tierData['price'],
                    'is_active' => $isActive,
                ]);
            }
        }

        // Update addons
        $addonsInput = $request->input('addons', []);
        $allAddons = Addon::all();
        foreach ($allAddons as $addon) {
            if (isset($addonsInput[$addon->id])) {
                $addonData = $addonsInput[$addon->id];
                $isActive = isset($addonData['is_active']) && ($addonData['is_active'] == '1' || $addonData['is_active'] == 'on' || $addonData['is_active'] === true);
                $addon->update([
                    'price' => $addonData['price'],
                    'is_active' => $isActive,
                ]);
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function serviceRequests()
    {
        $admin = Auth::guard('admin')->user();
        $sort = request('sort', 'newest');
        $filter = request('filter', 'all');

        $query = ServiceRequest::with(['customer', 'clothType', 'pricingTier', 'addons', 'branch', 'washingMachine', 'dryerMachine'])
            ->when($admin->branch_id, fn ($q) => $q->where('service_requests.branch_id', $admin->branch_id))
            ->leftJoin('customers', 'service_requests.customer_id', '=', 'customers.id')
            ->select('service_requests.*');

        match ($sort) {
            'oldest' => $query->orderBy('service_requests.id', 'asc'),
            'name_asc' => $query->orderBy(DB::raw("LOWER(COALESCE(customers.username, 'Walk-in'))"), 'asc')->orderBy('service_requests.id', 'desc'),
            'name_desc' => $query->orderBy(DB::raw("LOWER(COALESCE(customers.username, 'Walk-in'))"), 'desc')->orderBy('service_requests.id', 'desc'),
            'weight_desc' => $query->orderBy('service_requests.estimated_weight', 'desc')->orderBy('service_requests.id', 'desc'),
            'weight_asc' => $query->orderBy('service_requests.estimated_weight', 'asc')->orderBy('service_requests.id', 'desc'),
            'id_asc' => $query->orderBy('service_requests.id', 'asc'),
            'id_desc' => $query->orderBy('service_requests.id', 'desc'),
            'items_asc' => $query->orderBy('service_requests.quantity', 'asc')->orderBy('service_requests.id', 'desc'),
            'items_desc' => $query->orderBy('service_requests.quantity', 'desc')->orderBy('service_requests.id', 'desc'),
            'status_asc' => $query->orderByRaw("CASE service_requests.status WHEN 'pending' THEN 1 WHEN 'received' THEN 2 WHEN 'washing' THEN 3 WHEN 'drying' THEN 4 WHEN 'ready_for_pickup' THEN 5 WHEN 'ready' THEN 5 WHEN 'completed' THEN 6 WHEN 'cancelled' THEN 7 ELSE 8 END ASC")->orderBy('service_requests.id', 'desc'),
            'status_desc' => $query->orderByRaw("CASE service_requests.status WHEN 'cancelled' THEN 1 WHEN 'completed' THEN 2 WHEN 'ready_for_pickup' THEN 3 WHEN 'ready' THEN 3 WHEN 'drying' THEN 4 WHEN 'washing' THEN 5 WHEN 'received' THEN 6 WHEN 'pending' THEN 7 ELSE 8 END ASC")->orderBy('service_requests.id', 'desc'),
            default => $query->orderBy('service_requests.id', 'desc'),
        };

        $allRequests = $query->get();

        $requests = match ($filter) {
            'pending' => $allRequests->where('status', 'pending')->values(),
            'received' => $allRequests->where('status', 'received')->values(),
            'washing' => $allRequests->where('status', 'washing')->values(),
            'drying' => $allRequests->where('status', 'drying')->values(),
            'ready' => $allRequests->whereIn('status', ['ready_for_pickup', 'ready'])->values(),
            'completed' => $allRequests->where('status', 'completed')->values(),
            'cancelled' => $allRequests->where('status', 'cancelled')->values(),
            'processing' => $allRequests->whereIn('status', ['received', 'pending', 'washing', 'drying', 'ready_for_pickup', 'ready'])->values(),
            default => $allRequests,
        };
        return view('admin.service-requests', ['requests' => $requests, 'allRequests' => $allRequests, 'filter' => $filter, 'sort' => $sort]);
    }

    public function createServiceRequest()
    {
        return view('admin.service-request-create', [
            'branches' => Branch::where('is_active', true)->get(),
            'clothTypes' => ClothType::where('is_active', true)->with('pricingTiers')->get(),
            'addons' => Addon::all()
        ]);
    }

    public function storeWalkInServiceRequest(Request $request)
    {
        $adminBranchId = auth('admin')->user()->branch_id ?? Branch::first()->id;
        $maxWeight = ($request->input('cloth_type_id') == 2) ? 5 : 8;

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'cloth_type_id' => 'required|exists:cloth_types,id',
            'pricing_tier_id' => 'required|exists:pricing_tiers,id',
            'estimated_weight' => 'nullable|numeric|min:1|max:' . $maxWeight,
            'quantity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:1000',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:addons,id'
        ], [
            'estimated_weight.max' => "Weight cannot exceed {$maxWeight} kg for this item category.",
            'estimated_weight.min' => "Weight must be at least 1 kg.",
        ]);

        $branchId = $request->input('branch_id', $adminBranchId);
        $clothType = ClothType::findOrFail($data['cloth_type_id']);
        $tier = PricingTier::where('id', $data['pricing_tier_id'])
            ->where('cloth_type_id', $data['cloth_type_id'])
            ->firstOrFail();

        $comforterSize = null;
        if ($clothType->requires_size || !empty($tier->size)) {
            $quantity = 1;
            $estimatedWeight = null;
            $comforterSize = $tier->size ?? $tier->name;
            $basePrice = $tier->price;
        } else {
            $quantity = !empty($data['quantity']) ? (int)$data['quantity'] : 1;
            $estimatedWeight = $data['estimated_weight'] ?? ($tier->max_weight ?? $tier->min_weight ?? null);
            $comforterSize = null;
            $basePrice = $tier->price;
        }

        $addons = \App\Models\Addon::whereIn('id', $data['addon_ids'] ?? [])->where('is_active', true)->get();
        $customer = Customer::create([
            'username' => $data['customer_name'] . ' (Walk-in ' . now()->format('His') . ')',
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make(Str::random(32)),
            'branch_id' => $branchId
        ]);

        $serviceRequest = ServiceRequest::create([
            'customer_id' => $customer->id,
            'branch_id' => $branchId,
            'cloth_type_id' => $data['cloth_type_id'],
            'pricing_tier_id' => $tier->id,
            'estimated_weight' => $estimatedWeight,
            'actual_weight' => $estimatedWeight,
            'comforter_size' => $comforterSize,
            'quantity' => $quantity,
            'actual_quantity' => $quantity,
            'notes' => $data['notes'] ?? null,
            'estimated_price' => $basePrice + $addons->sum('price'),
            'final_price' => $basePrice + $addons->sum('price'),
            'status' => 'received'
        ]);

        $serviceRequest->addons()->attach($addons->mapWithKeys(fn ($addon) => [$addon->id => ['price' => $addon->price]]));

        ServiceRequestStatusHistory::create([
            'service_request_id' => $serviceRequest->id,
            'status' => 'received',
            'changed_by' => auth('admin')->id(),
            'started_at' => now(),
        ]);

        return redirect()->route('admin.service-requests.show', $serviceRequest)->with('success', 'Walk-in service request added.');
    }

    public function showServiceRequest(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load(['customer', 'branch', 'clothType', 'pricingTier', 'addons', 'statusHistories', 'washingMachine', 'dryerMachine']);
        
        $branchId = $serviceRequest->branch_id;
        $washers = Machine::where('branch_id', $branchId)
            ->where('type', 'washer')
            ->where(function($q) use ($serviceRequest) {
                $q->where('status', 'available')
                  ->orWhere('id', $serviceRequest->washing_machine_id);
            })
            ->orderBy('id', 'asc')
            ->get();

        $dryers = Machine::where('branch_id', $branchId)
            ->where('type', 'dryer')
            ->where(function($q) use ($serviceRequest) {
                $q->where('status', 'available')
                  ->orWhere('id', $serviceRequest->dryer_machine_id);
            })
            ->orderBy('id', 'asc')
            ->get();

        $tiers = PricingTier::where('cloth_type_id', $serviceRequest->cloth_type_id)->where('is_active', true)->get();
        return view('admin.service-request-details', compact('serviceRequest', 'washers', 'dryers', 'tiers'));
    }

    public function acceptServiceRequest(ServiceRequest $serviceRequest)
    {
        $admin = Auth::guard('admin')->user();
        $serviceRequest->update(['admin_id' => $admin->id, 'accepted_by' => $admin->id, 'status' => 'received']);
        ServiceRequestStatusHistory::create(['service_request_id' => $serviceRequest->id, 'status' => 'received', 'changed_by' => $admin->id, 'started_at' => now()]);
        return back()->with('success', 'Service request accepted.');
    }

    public function updateServiceRequestStatus(Request $request, ServiceRequest $serviceRequest)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,received,washing,drying,ready_for_pickup,completed,cancelled',
            'washing_machine_id' => 'nullable|exists:machines,id',
            'dryer_machine_id' => 'nullable|exists:machines,id',
        ]);

        $admin = Auth::guard('admin')->user();
        $targetStatus = $data['status'];

        if ($serviceRequest->status === 'completed' && $targetStatus === 'cancelled') {
            return back()->withErrors(['status' => 'A completed request cannot be cancelled.']);
        }
        if ($targetStatus === 'completed' && $serviceRequest->final_price === null) {
            return back()->withErrors(['status' => 'Verify the laundry and calculate the final price before completing this request.']);
        }

        // Auto-assign available washing machine (sorted ASC) if jumping to washing without machine specified
        if ($targetStatus === 'washing' && empty($data['washing_machine_id'])) {
            $availableWasher = Machine::where('branch_id', $serviceRequest->branch_id)
                ->where('type', 'washer')
                ->where('status', 'available')
                ->orderBy('id', 'asc')
                ->first();

            if (!$availableWasher) {
                return back()->with('error', 'No available washing machine found in this branch. Please add or free a washer unit before moving to washing.');
            }

            $data['washing_machine_id'] = $availableWasher->id;
        }

        // Auto-assign available dryer machine (sorted ASC) if jumping to drying without machine specified
        if ($targetStatus === 'drying' && empty($data['dryer_machine_id'])) {
            $availableDryer = Machine::where('branch_id', $serviceRequest->branch_id)
                ->where('type', 'dryer')
                ->where('status', 'available')
                ->orderBy('id', 'asc')
                ->first();

            if (!$availableDryer) {
                return back()->with('error', 'No available dryer machine found in this branch. Please add or free a dryer unit before moving to drying.');
            }

            $data['dryer_machine_id'] = $availableDryer->id;
        }

        DB::transaction(function () use ($serviceRequest, $data, $targetStatus, $admin) {
            $current = $serviceRequest->statusHistories()->whereNull('ended_at')->latest()->first();
            if ($current) $current->update(['ended_at' => now()]);

            $updatePayload = ['status' => $targetStatus];
            if (!$serviceRequest->admin_id && $admin) {
                $updatePayload['admin_id'] = $admin->id;
            }
            if (in_array($targetStatus, ['received', 'washing', 'drying', 'ready_for_pickup', 'ready', 'completed']) && !$serviceRequest->accepted_by && $admin) {
                $updatePayload['accepted_by'] = $admin->id;
            }

            if (!empty($data['washing_machine_id'])) {
                if ($serviceRequest->washing_machine_id && $serviceRequest->washing_machine_id != $data['washing_machine_id']) {
                    Machine::where('id', $serviceRequest->washing_machine_id)->update(['status' => 'available']);
                }
                $updatePayload['washing_machine_id'] = $data['washing_machine_id'];
                Machine::where('id', $data['washing_machine_id'])->update(['status' => 'in_use']);
            }

            if (!empty($data['dryer_machine_id'])) {
                if ($serviceRequest->dryer_machine_id && $serviceRequest->dryer_machine_id != $data['dryer_machine_id']) {
                    Machine::where('id', $serviceRequest->dryer_machine_id)->update(['status' => 'available']);
                }
                $updatePayload['dryer_machine_id'] = $data['dryer_machine_id'];
                Machine::where('id', $data['dryer_machine_id'])->update(['status' => 'in_use']);

                if ($serviceRequest->washing_machine_id) {
                    Machine::where('id', $serviceRequest->washing_machine_id)->update(['status' => 'available']);
                }
            }

            if (in_array($targetStatus, ['ready_for_pickup', 'ready', 'completed', 'cancelled'])) {
                if ($serviceRequest->dryer_machine_id) {
                    Machine::where('id', $serviceRequest->dryer_machine_id)->update(['status' => 'available']);
                }
                if ($serviceRequest->washing_machine_id) {
                    Machine::where('id', $serviceRequest->washing_machine_id)->update(['status' => 'available']);
                }
            }

            $serviceRequest->update($updatePayload);

            ServiceRequestStatusHistory::create([
                'service_request_id' => $serviceRequest->id,
                'status' => $targetStatus,
                'changed_by' => $admin->id,
                'started_at' => now()
            ]);

            if ($targetStatus === 'completed') {
                Sale::firstOrCreate(
                    ['service_request_id' => $serviceRequest->id],
                    ['branch_id' => $serviceRequest->branch_id, 'amount' => $serviceRequest->final_price, 'sold_at' => now()]
                );
            }
        });

        $msg = 'Status updated to ' . ucfirst($targetStatus) . '.';
        if ($targetStatus === 'washing' && !empty($data['washing_machine_id'])) {
            $m = Machine::find($data['washing_machine_id']);
            if ($m) $msg = "Moved to Washing and automatically assigned to {$m->name}.";
        } elseif ($targetStatus === 'drying' && !empty($data['dryer_machine_id'])) {
            $m = Machine::find($data['dryer_machine_id']);
            if ($m) $msg = "Moved to Drying and automatically assigned to {$m->name}.";
        }

        return back()->with('success', $msg);
    }

    public function updateServiceRequestDetails(Request $request, ServiceRequest $serviceRequest)
    {
        $clothType = $serviceRequest->clothType;
        $admin = Auth::guard('admin')->user();

        // 1. Comforters: Verify and confirm comforter size (single, double, queen, king), piece count is always 1
        if ($clothType->requires_size) {
            $data = $request->validate([
                'pricing_tier_id' => 'required|exists:pricing_tiers,id',
            ]);

            $tier = PricingTier::where('id', $data['pricing_tier_id'])
                ->where('cloth_type_id', $serviceRequest->cloth_type_id)
                ->firstOrFail();

            $verifiedQuantity = 1;
            $basePrice = $tier->price * $verifiedQuantity;
            $addonTotal = $serviceRequest->addons()->get()->sum(fn ($addon) => $addon->pivot->price ?? $addon->price);
            $finalPrice = $basePrice + $addonTotal;

            $serviceRequest->update([
                'pricing_tier_id' => $tier->id,
                'comforter_size' => $tier->size ?? $tier->name,
                'quantity' => 1,
                'actual_quantity' => 1,
                'final_price' => $finalPrice,
                'status' => 'received',
                'admin_id' => $serviceRequest->admin_id ?? $admin?->id,
                'accepted_by' => $serviceRequest->accepted_by ?? $admin?->id,
                'weight_verification_status' => null,
                'verification_note' => null,
            ]);

            ServiceRequestStatusHistory::create([
                'service_request_id' => $serviceRequest->id,
                'status' => 'received',
                'changed_by' => $admin?->id,
                'started_at' => now(),
            ]);

            return back()->with('success', "Comforter size verified as " . ($tier->size ?? $tier->name) . " (₱" . number_format($tier->price, 0) . "). Status updated to Received.");
        }

        // 2. Weight-based types (Regular clothes, Beddings/Towels/Curtains)
        $maxWeight = ($serviceRequest->cloth_type_id == 2) ? 5 : 8;
        $data = $request->validate([
            'actual_weight' => 'required|numeric|min:1|max:' . $maxWeight,
            'actual_quantity' => 'required|integer|min:1',
        ], [
            'actual_weight.max' => "Scale weight cannot exceed {$maxWeight} kg for this item category.",
            'actual_weight.min' => "Scale weight must be at least 1 kg.",
        ]);

        $tier = PricingTier::where('id', $serviceRequest->pricing_tier_id)->where('cloth_type_id', $serviceRequest->cloth_type_id)->firstOrFail();
        
        // Find matching weight tier if weight changed for weight-based types
        if (!empty($data['actual_weight'])) {
            $weightTier = PricingTier::where('cloth_type_id', $serviceRequest->cloth_type_id)
                ->where('is_active', true)
                ->where(function ($query) use ($data) {
                    $query->whereNull('min_weight')->orWhere('min_weight', '<=', $data['actual_weight']);
                })->where(function ($query) use ($data) {
                    $query->whereNull('max_weight')->orWhere('max_weight', '>=', $data['actual_weight']);
                })->first();
            if ($weightTier) {
                $tier = $weightTier;
                $data['pricing_tier_id'] = $tier->id;
            }
        }

        $addonTotal = $serviceRequest->addons()->get()->sum(fn ($addon) => $addon->pivot->price ?? $addon->price);
        $verifiedQuantity = (int)$data['actual_quantity'];
        $basePrice = $tier->price;
        $finalPrice = $basePrice + $addonTotal;

        $submittedWeight = $serviceRequest->estimated_weight !== null ? (float)$serviceRequest->estimated_weight : null;
        $submittedQuantity = (int)$serviceRequest->quantity;
        $actualWeight = (float)$data['actual_weight'];
        $actualQuantity = $verifiedQuantity;

        $weightMatched = ($submittedWeight !== null) ? (abs($actualWeight - $submittedWeight) < 0.05) : true;
        $quantityMatched = ($actualQuantity === $submittedQuantity);
        $isCorrect = $weightMatched && $quantityMatched;

        if ($isCorrect) {
            $verificationStatus = 'correct';
            $verificationNote = "Laundry weight ({$actualWeight} kg) and item count ({$actualQuantity} pcs) were verified as correct.";
            $flashMsg = "Laundry verified as CORRECT ({$actualWeight} kg, {$actualQuantity} pcs). Status automatically updated to Received.";
        } else {
            $verificationStatus = 'incorrect';
            $adjustments = [];
            if (!$weightMatched) {
                $adjustments[] = "Weight: {$actualWeight} kg (submitted: " . ($submittedWeight !== null ? "{$submittedWeight} kg" : "None") . ")";
            }
            if (!$quantityMatched) {
                $adjustments[] = "Items: {$actualQuantity} pcs (submitted: {$submittedQuantity} pcs)";
            }
            $verificationNote = "Laundry details updated by staff during weigh-in: " . implode(', ', $adjustments) . ". Final price: PHP " . number_format($finalPrice, 0) . ".";
            $flashMsg = "Laundry verified with adjustments (" . implode(', ', $adjustments) . "). Customer notified and status updated to Received.";
        }

        $serviceRequest->update([
            'pricing_tier_id' => $data['pricing_tier_id'] ?? $tier->id,
            'actual_weight' => $actualWeight,
            'actual_quantity' => $actualQuantity,
            'final_price' => $finalPrice,
            'weight_verification_status' => $verificationStatus,
            'verification_note' => $verificationNote,
            'status' => 'received',
            'admin_id' => $serviceRequest->admin_id ?? $admin?->id,
            'accepted_by' => $serviceRequest->accepted_by ?? $admin?->id,
        ]);

        ServiceRequestStatusHistory::create([
            'service_request_id' => $serviceRequest->id,
            'status' => 'received',
            'changed_by' => $admin?->id,
            'started_at' => now(),
        ]);

        return back()->with('success', $flashMsg);
    }

    public function machines()
    {
        $admin = Auth::guard('admin')->user();
        $machines = Machine::with(['branch', 'activeWashingRequest.customer', 'activeDryerRequest.customer'])
            ->when($admin->branch_id, fn ($q) => $q->where('branch_id', $admin->branch_id))
            ->latest()
            ->get();
        $branches = Branch::all();
        return view('admin.machines', compact('machines', 'branches'));
    }
    public function storeMachine(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $branchId = $admin->branch_id ?? Branch::first()->id ?? 1;

        $request->validate([
            'type' => 'required|in:washer,dryer',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'name' => 'nullable|string|max:100',
        ]);

        $typeRaw = $request->type;
        $typeLabel = ucfirst($typeRaw);
        $brand = trim($request->brand ?? '');
        $model = trim($request->model ?? '');

        // Auto-generate name if empty or default
        if ($request->filled('name')) {
            $name = trim($request->name);
        } else {
            $count = Machine::where('branch_id', $branchId)->where('type', $typeRaw)->count() + 1;
            $numberStr = str_pad($count, 2, '0', STR_PAD_LEFT);
            $name = "{$typeLabel} #{$numberStr}";
        }

        $machine = Machine::create([
            'branch_id' => $branchId,
            'name' => $name,
            'brand' => $brand ?: null,
            'model' => $model ?: null,
            'type' => $typeRaw,
            'status' => 'available',
        ]);

        return back()->with('success', "Machine '{$machine->name}' added successfully.");
    }

    public function updateMachine(Request $request, Machine $machine)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:washer,dryer',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'status' => 'required|in:available,in_use,maintenance',
        ]);

        $machine->update([
            'name' => trim($data['name']),
            'type' => $data['type'],
            'brand' => trim($data['brand'] ?? '') ?: null,
            'model' => trim($data['model'] ?? '') ?: null,
            'status' => $data['status'],
        ]);

        return back()->with('success', "Machine '{$machine->name}' updated successfully.");
    }

    public function destroyMachine(Machine $machine)
    {
        $activeReq = $machine->active_service_request;
        if ($activeReq || $machine->status === 'in_use') {
            return back()->with('error', "Cannot delete '{$machine->name}' because it is currently assigned to an active service request (#REQ-{$activeReq?->id}).");
        }

        $name = $machine->name;
        $machine->delete();

        return back()->with('success', "Machine '{$name}' deleted successfully.");
    }

    public function toggleMachineStatus(Machine $machine)
    {
        if ($machine->status === 'in_use') {
            return back()->with('error', "Cannot toggle '{$machine->name}' because it is currently in use with an active laundry order.");
        }

        $newStatus = ($machine->status === 'available') ? 'maintenance' : 'available';
        $machine->update(['status' => $newStatus]);
        $statusLabel = $newStatus === 'available' ? 'Available' : 'Maintenance';

        return back()->with('success', "Machine '{$machine->name}' marked as {$statusLabel}.");
    }
    public function sales()
    {
        $period = request('period', 'today');
        $sort = request('sort', 'newest');
        $admin = Auth::guard('admin')->user();

        $query = Sale::with(['serviceRequest.customer', 'serviceRequest.clothType', 'branch'])
            ->when($admin->branch_id, fn ($q) => $q->where('sales.branch_id', $admin->branch_id))
            ->leftJoin('service_requests', 'sales.service_request_id', '=', 'service_requests.id')
            ->leftJoin('customers', 'service_requests.customer_id', '=', 'customers.id')
            ->select('sales.*');

        if ($period === 'today') {
            $query->whereDate('sales.sold_at', today());
        } elseif ($period === 'week') {
            $query->whereBetween('sales.sold_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereMonth('sales.sold_at', now()->month)->whereYear('sales.sold_at', now()->year);
        }

        match ($sort) {
            'oldest' => $query->orderBy('sales.sold_at', 'asc'),
            'id_asc' => $query->orderBy('sales.service_request_id', 'asc'),
            'id_desc' => $query->orderBy('sales.service_request_id', 'desc'),
            'name_asc' => $query->orderBy(DB::raw("LOWER(COALESCE(customers.username, 'Walk-in'))"), 'asc')->orderBy('sales.id', 'desc'),
            'name_desc' => $query->orderBy(DB::raw("LOWER(COALESCE(customers.username, 'Walk-in'))"), 'desc')->orderBy('sales.id', 'desc'),
            'price_desc' => $query->orderBy('sales.amount', 'desc')->orderBy('sales.id', 'desc'),
            'price_asc' => $query->orderBy('sales.amount', 'asc')->orderBy('sales.id', 'desc'),
            default => $query->orderBy('sales.sold_at', 'desc'),
        };

        $sales = $query->get();

        $totalSales = $sales->sum('amount');
        $transactionCount = $sales->count();
        $averageSale = $transactionCount ? $sales->avg('amount') : 0;

        return view('admin.sales', [
            'sales' => $sales,
            'totalSales' => $totalSales,
            'transactionCount' => $transactionCount,
            'averageSale' => $averageSale,
            'period' => $period,
            'sort' => $sort,
        ]);
    }
}
