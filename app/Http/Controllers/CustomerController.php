<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LoginLog;
use App\Models\Addon;
use App\Models\Branch;
use App\Models\ClothType;
use App\Models\PricingTier;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function showRegister()
    {
        return view('customer.register');
    }

    public function chooseBranch()
    {
        $branches = Schema::hasTable('branches') ? Branch::where('is_active', true)->get() : collect();
        return view('customer.choose-branch', compact('branches'));
    }

    public function selectBranch(Request $request)
    {
        $data = $request->validate(['branch_id' => 'required|exists:branches,id']);
        session(['selected_branch_id' => $data['branch_id']]);
        return redirect()->route('customer.login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:customers,username',
            'password' => 'required|min:6',
        ]);

        Customer::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'branch_id' => session('selected_branch_id'),
        ]);

        return redirect()->route('customer.login')->with('success', 'Registered! Please login.');
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
            if ($customer->branch_id === null && session('selected_branch_id')) $customer->update(['branch_id' => session('selected_branch_id')]);

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
        $log = LoginLog::where('user_id', $customer->id)
            ->where('user_type', 'customer')
            ->whereNull('logout_at')
            ->latest()
            ->first();

        if ($log) {
            $log->logout_at = now();
            $log->save();
        }

        Auth::guard('customer')->logout();

        return redirect()->route('customer.login');
    }

    public function profile()
    {
        return view('customer.profile', ['branches' => Branch::where('is_active', true)->get()]);
    }

    public function updateBranch(Request $request)
    {
        $data = $request->validate(['branch_id' => 'required|exists:branches,id']);
        Auth::guard('customer')->user()->update(['branch_id' => $data['branch_id']]);
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
    public function dashboard()
    {
        return view('customer.dashboard', ['serviceRequests' => ServiceRequest::where('customer_id', Auth::guard('customer')->id())->with('branch')->latest()->get()]);
    }

    public function requests()
    {
        $serviceRequests = ServiceRequest::where('customer_id', Auth::guard('customer')->id())->with('branch')->latest()->get();
        return view('customer.requests', compact('serviceRequests'));
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
            'addons' => Addon::where('is_active', true)->get(),
        ]);
    }

    public function storeServiceRequest(Request $request)
    {
        $data = $request->validate([
            'cloth_type_id' => 'required|exists:cloth_types,id', 'estimated_weight' => 'required|numeric|min:0.1',
            'quantity' => 'required|integer|min:1', 'notes' => 'nullable|string|max:1000',
            'addon_ids' => 'nullable|array', 'addon_ids.*' => 'exists:addons,id',
        ]);
        $customer = Auth::guard('customer')->user();
        abort_unless($customer->branch_id, 422, 'Please select a branch in your profile before submitting a service request.');
        $tier = PricingTier::where('cloth_type_id', $data['cloth_type_id'])->where('is_active', true)->where(function ($query) use ($data) { $query->whereNull('min_weight')->orWhere('min_weight', '<=', $data['estimated_weight']); })->where(function ($query) use ($data) { $query->whereNull('max_weight')->orWhere('max_weight', '>=', $data['estimated_weight']); })->first() ?? PricingTier::where('cloth_type_id', $data['cloth_type_id'])->where('is_active', true)->firstOrFail();
        $addons = Addon::whereIn('id', $data['addon_ids'] ?? [])->where('is_active', true)->get();
        $estimatedPrice = ($tier->price * $data['quantity']) + $addons->sum('price');
        $requestModel = ServiceRequest::create(array_merge($data, ['customer_id' => $customer->id, 'branch_id' => $customer->branch_id, 'pricing_tier_id' => $tier->id, 'estimated_price' => $estimatedPrice, 'status' => 'pending']));
        $requestModel->addons()->attach($addons->mapWithKeys(fn ($addon) => [$addon->id => ['price' => $addon->price]]));
        return redirect()->route('customer.dashboard')->with('success', 'Service request submitted successfully.');
    }
    
}
