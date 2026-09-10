<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LoginLog;
use App\Models\Addon;
use App\Models\Branch;
use App\Models\ClothType;
use App\Models\PricingTier;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function showRegister()
    {
        return view('customer.register');
    }

    public function home()
    {
        $branches = Branch::where('is_active', true)->get();
        return view('home', compact('branches'));
    }

    public function chooseBranch()
    {
        $branches = Branch::where('is_active', true)->get();
        return view('customer.choose-branch', compact('branches'));
    }

    public function selectBranch(Request $request)
    {
        $data = $request->validate(['branch_id' => 'required|exists:branches,id']);
        session(['selected_branch_id' => $data['branch_id']]);

        if (Auth::guard('customer')->check()) {
            Auth::guard('customer')->user()->update(['branch_id' => $data['branch_id']]);
            return redirect()->route('customer.dashboard')->with('success', 'Branch selected successfully!');
        }

        return redirect()->route('customer.login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:customers,username',
            'phone' => 'nullable|string|max:25',
            'password' => 'required|min:6',
        ]);

        $branchId = session('selected_branch_id');

        $customer = Customer::create([
            'username' => $request->username,
            'phone' => $request->phone ?? null,
            'password' => Hash::make($request->password),
            'branch_id' => $branchId,
        ]);

        // Automatically log in the new customer
        Auth::guard('customer')->login($customer);

        // Record the login log
        LoginLog::create([
            'user_id' => $customer->id,
            'user_type' => 'customer',
            'username' => $customer->username,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => now(),
            'status' => 'success',
        ]);

        if (!$customer->branch_id) {
            return redirect()->route('customer.choose-branch')->with('info', 'Please choose your preferred laundry branch to complete your setup.');
        }

        return redirect()->route('customer.dashboard')->with('success', 'Account created successfully! Welcome to Spinzone Laundry.');
    }

    public function showLogin()
    {
        return view('customer.login', ['branch' => Branch::find(session('selected_branch_id'))]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($credentials)) {
            $customer = Auth::guard('customer')->user();
            if ($customer->branch_id === null && session('selected_branch_id')) {
                $customer->update(['branch_id' => session('selected_branch_id')]);
            }

            // Record the SUCCESSFUL login
            LoginLog::create([
                'user_id' => $customer->id,
                'user_type' => 'customer',
                'username' => $customer->username,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
                'status' => 'success',
            ]);

            if (!$customer->branch_id) {
                return redirect()->route('customer.choose-branch')->with('info', 'Please choose your preferred laundry branch.');
            }

            return redirect('/customer/dashboard');
        }

        // Record the FAILED login attempt
        LoginLog::create([
            'user_id' => 0,
            'user_type' => 'customer',
            'username' => $request->username,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => now(),
            'status' => 'failed',
        ]);

        return back()
            ->withInput($request->only('username'))
            ->withErrors(['username' => 'Invalid username or password']);
    }

    public function logout(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        // Find their MOST RECENT successful login log (that hasn't logged out yet)
        if ($customer) {
            $log = LoginLog::where('user_id', $customer->id)
                ->where('user_type', 'customer')
                ->whereNull('logout_at')
                ->latest()
                ->first();

            if ($log) {
                $log->logout_at = now();
                $log->save();
            }
        }

        Auth::guard('customer')->logout();

        return redirect()->route('customer.login');
    }

    public function profile()
    {
        return view('customer.profile', ['branches' => Branch::where('is_active', true)->get()]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'phone' => 'nullable|string|max:25',
        ]);

        Auth::guard('customer')->user()->update($data);
        return back()->with('success', 'Phone number updated successfully.');
    }

    public function updateBranch(Request $request)
    {
        $data = $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);
        Auth::guard('customer')->user()->update($data);
        return back()->with('success', 'Branch updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        $customer = Auth::guard('customer')->user();

        if (!Hash::check($request->current_password, $customer->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $customer->password = Hash::make($request->new_password);
        $customer->save();

        return back()->with('success', 'Password updated successfully!');
    }
    public function about()
    {
        $branch = Auth::guard('customer')->user()->branch ?? Branch::first();
        return view('customer.about', compact('branch'));
    }

    public function dashboard()
    {
        $serviceRequests = ServiceRequest::where('customer_id', Auth::guard('customer')->id())
            ->with(['branch', 'clothType', 'pricingTier'])
            ->latest()
            ->get();

        return view('customer.dashboard', compact('serviceRequests'));
    }

    public function requests()
    {
        $customerId = Auth::guard('customer')->id();
        $sort = request('sort', 'newest');
        $filter = request('filter', 'all');

        $query = ServiceRequest::with(['clothType', 'pricingTier', 'addons', 'branch', 'washingMachine', 'dryerMachine'])
            ->where('service_requests.customer_id', $customerId);

        match ($sort) {
            'oldest' => $query->orderBy('service_requests.id', 'asc'),
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
            default => $allRequests,
        };

        return view('customer.requests', [
            'requests' => $requests,
            'allRequests' => $allRequests,
            'filter' => $filter,
            'sort' => $sort,
        ]);
    }

    public function showRequest(ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->customer_id === Auth::guard('customer')->id(), 404);
        $serviceRequest->load(['branch', 'clothType', 'addons', 'statusHistories']);
        return view('customer.request-details', compact('serviceRequest'));
    }

    public function createServiceRequest()
    {
        return view('customer.service-request', [
            'clothTypes' => ClothType::where('is_active', true)->with('pricingTiers')->get(),
            'addons' => Addon::all(),
        ]);
    }

    public function storeServiceRequest(Request $request)
    {
        $maxWeight = ($request->input('cloth_type_id') == 2) ? 5 : 8;

        $data = $request->validate([
            'cloth_type_id' => 'required|exists:cloth_types,id',
            'pricing_tier_id' => 'required|exists:pricing_tiers,id',
            'estimated_weight' => 'nullable|numeric|min:1|max:' . $maxWeight,
            'quantity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:1000',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:addons,id',
        ], [
            'estimated_weight.max' => "Weight cannot exceed {$maxWeight} kg for this item category.",
            'estimated_weight.min' => "Weight must be at least 1 kg.",
        ]);

        $customer = Auth::guard('customer')->user();
        abort_unless($customer->branch_id, 422, 'Please select a branch in your profile before submitting a service request.');

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

        $addons = Addon::whereIn('id', $data['addon_ids'] ?? [])->where('is_active', true)->get();
        $estimatedPrice = $basePrice + $addons->sum('price');

        $requestModel = ServiceRequest::create([
            'customer_id' => $customer->id,
            'branch_id' => $customer->branch_id,
            'cloth_type_id' => $data['cloth_type_id'],
            'pricing_tier_id' => $tier->id,
            'estimated_weight' => $estimatedWeight,
            'comforter_size' => $comforterSize,
            'quantity' => $quantity,
            'notes' => $data['notes'] ?? null,
            'estimated_price' => $estimatedPrice,
            'status' => 'pending'
        ]);

        $requestModel->addons()->attach($addons->mapWithKeys(fn ($addon) => [$addon->id => ['price' => $addon->price]]));

        ServiceRequestStatusHistory::create([
            'service_request_id' => $requestModel->id,
            'status' => 'pending',
            'started_at' => now(),
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Service request submitted successfully.');
    }
}
