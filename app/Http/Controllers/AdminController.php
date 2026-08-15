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

        $log = LoginLog::where('user_id', $admin->id)
            ->where('user_type', 'admin')
            ->whereNull('logout_at')
            ->latest()
            ->first();

        if ($log) {
            $log->logout_at = now();
            $log->save();
        }

        Auth::guard('admin')->logout();

        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $requests = ServiceRequest::with('customer')->latest()->take(5)->get();
        return view('admin.dashboard', [
            'requestCount' => ServiceRequest::count(),
            'pendingCount' => ServiceRequest::where('status', 'received')->count(),
            'activeCount' => ServiceRequest::whereIn('status', ['received', 'washing', 'drying', 'ready_for_pickup'])->count(),
            'salesTotal' => Sale::sum('amount'),
            'requests' => $requests,
        ]);
    }

    public function loginLogs()
    {
        $logs = LoginLog::latest()->get();
        return view('admin.login-logs', ['logs' => $logs]);
    }

    public function customers()
    {
        $customers = Customer::all();
        return view('admin.customers', ['customers' => $customers]);
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6',
        ]);

        $customer = Customer::find($id);
        $customer->password = Hash::make($request->password);
        $customer->save();

        return redirect()->route('admin.customers')->with('success', 'Password reset successfully for ' . $customer->username);
    }

    public function profile()
    {
        return view('admin.profile', ['branch' => Auth::guard('admin')->user()->branch]);
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $data = $request->validate(['username' => 'required|string|max:255|unique:admins,username,' . $admin->id, 'branch_name' => 'required|string|max:255', 'branch_address' => 'required|string|max:255']);
        $admin->update(['username' => $data['username']]);
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

    public function serviceRequests()
    {
        $admin = Auth::guard('admin')->user();
        $allRequests = ServiceRequest::with(['customer', 'clothType', 'addons', 'branch'])->when($admin->branch_id, fn ($q) => $q->where('branch_id', $admin->branch_id))->latest()->get();
        $filter = request('filter', 'all');
        $requests = match ($filter) {
            'pending' => $allRequests->where('status', 'received')->values(),
            'processing' => $allRequests->whereIn('status', ['received', 'washing', 'drying', 'ready_for_pickup'])->values(),
            'completed' => $allRequests->where('status', 'completed')->values(),
            default => $allRequests,
        };
        return view('admin.service-requests', ['requests' => $requests, 'allRequests' => $allRequests, 'filter' => $filter]);
    }

    public function createServiceRequest() { return view('admin.service-request-create', ['branches' => Branch::where('is_active', true)->get(), 'clothTypes' => \App\Models\ClothType::where('is_active', true)->get(), 'addons' => \App\Models\Addon::where('is_active', true)->get()]); }

    public function storeWalkInServiceRequest(Request $request)
    {
        $data = $request->validate(['customer_name' => 'required|string|max:255', 'branch_id' => 'required|exists:branches,id', 'cloth_type_id' => 'required|exists:cloth_types,id', 'estimated_weight' => 'required|numeric|min:0.1', 'quantity' => 'required|integer|min:1', 'notes' => 'nullable|string|max:1000', 'addon_ids' => 'nullable|array', 'addon_ids.*' => 'exists:addons,id']);
        $tier = PricingTier::where('cloth_type_id', $data['cloth_type_id'])->where('is_active', true)->where('min_weight', '<=', $data['estimated_weight'])->where(function ($q) use ($data) { $q->whereNull('max_weight')->orWhere('max_weight', '>=', $data['estimated_weight']); })->first() ?? PricingTier::where('cloth_type_id', $data['cloth_type_id'])->where('is_active', true)->firstOrFail();
        $addons = \App\Models\Addon::whereIn('id', $data['addon_ids'] ?? [])->where('is_active', true)->get();
        $customer = Customer::create(['username' => $data['customer_name'] . ' (Walk-in ' . now()->format('His') . ')', 'password' => Hash::make(Str::random(32)), 'branch_id' => $data['branch_id']]);
        $serviceRequest = ServiceRequest::create(['customer_id' => $customer->id, 'branch_id' => $data['branch_id'], 'cloth_type_id' => $data['cloth_type_id'], 'pricing_tier_id' => $tier->id, 'estimated_weight' => $data['estimated_weight'], 'quantity' => $data['quantity'], 'notes' => $data['notes'] ?? null, 'estimated_price' => ($tier->price * $data['quantity']) + $addons->sum('price'), 'status' => 'received']);
        $serviceRequest->addons()->attach($addons->mapWithKeys(fn ($addon) => [$addon->id => ['price' => $addon->price]]));
        return redirect()->route('admin.service-requests.show', $serviceRequest)->with('success', 'Walk-in service request added.');
    }

    public function showServiceRequest(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load(['customer', 'branch', 'clothType', 'pricingTier', 'addons', 'statusHistories', 'washingMachine', 'dryerMachine']);
        $machines = Machine::where('branch_id', $serviceRequest->branch_id)->where('status', 'available')->get();
        $tiers = PricingTier::where('cloth_type_id', $serviceRequest->cloth_type_id)->where('is_active', true)->get();
        return view('admin.service-request-details', compact('serviceRequest', 'machines', 'tiers'));
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
        $data = $request->validate(['status' => 'required|in:received,washing,drying,ready_for_pickup,completed,cancelled']);
        $admin = Auth::guard('admin')->user();
        $nextStatuses = ['received' => 'washing', 'washing' => 'drying', 'drying' => 'ready_for_pickup', 'ready_for_pickup' => 'completed'];
        if ($serviceRequest->status === 'completed' && $data['status'] === 'cancelled') {
            return back()->withErrors(['status' => 'A completed request cannot be cancelled.']);
        }
        if ($data['status'] === 'completed' && $serviceRequest->final_price === null) {
            return back()->withErrors(['status' => 'Verify the laundry and calculate the final price before completing this request.']);
        }
        DB::transaction(function () use ($serviceRequest, $data, $admin) {
            $current = $serviceRequest->statusHistories()->whereNull('ended_at')->latest()->first();
            if ($current) $current->update(['ended_at' => now()]);
            $serviceRequest->update(['status' => $data['status']]);
            ServiceRequestStatusHistory::create(['service_request_id' => $serviceRequest->id, 'status' => $data['status'], 'changed_by' => $admin->id, 'started_at' => now()]);
            if ($data['status'] === 'completed') Sale::firstOrCreate(['service_request_id' => $serviceRequest->id], ['branch_id' => $serviceRequest->branch_id, 'amount' => $serviceRequest->final_price, 'sold_at' => now()]);
        });
        return back()->with('success', 'Status updated.');
    }

    public function updateServiceRequestDetails(Request $request, ServiceRequest $serviceRequest)
    {
        $data = $request->validate(['actual_weight' => 'nullable|numeric|min:0.1|required_without:actual_quantity', 'actual_quantity' => 'nullable|integer|min:1|required_without:actual_weight']);
        $tier = PricingTier::where('id', $serviceRequest->pricing_tier_id)->where('cloth_type_id', $serviceRequest->cloth_type_id)->firstOrFail();
        if (!empty($data['actual_weight'])) {
            $weightTier = PricingTier::where('cloth_type_id', $serviceRequest->cloth_type_id)->where('is_active', true)->where(function ($query) use ($data) {
                $query->whereNull('min_weight')->orWhere('min_weight', '<=', $data['actual_weight']);
            })->where(function ($query) use ($data) {
                $query->whereNull('max_weight')->orWhere('max_weight', '>=', $data['actual_weight']);
            })->first();
            if ($weightTier) { $tier = $weightTier; $data['pricing_tier_id'] = $tier->id; }
        }
        $addonTotal = $serviceRequest->addons()->get()->sum(fn ($addon) => $addon->pivot->price ?? $addon->price);
        $verifiedQuantity = $data['actual_quantity'] ?? $serviceRequest->quantity;
        $serviceRequest->update(array_merge($data, ['final_price' => ($tier->price * $verifiedQuantity) + $addonTotal]));
        return back()->with('success', 'Request details and final price updated.');
    }

    public function machines() { return view('admin.machines', ['machines' => Machine::with('branch')->latest()->get()]); }
    public function storeMachine(Request $request) { $data = $request->validate(['branch_id' => 'required|exists:branches,id', 'name' => 'required|string|max:100', 'type' => 'required|in:washer,dryer']); Machine::create($data); return back()->with('success', 'Machine added.'); }
    public function sales() { return view('admin.sales', ['sales' => Sale::with('serviceRequest.customer', 'branch')->latest('sold_at')->get()]); }
}
