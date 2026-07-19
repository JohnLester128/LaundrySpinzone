<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:customers,username',
            'password' => 'required|min:6',
        ]);

        Customer::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.login')->with('success', 'Registered! Please login.');
    }

    public function showLogin()
    {
        return view('customer.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($credentials)) {
            $customer = Auth::guard('customer')->user();

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
        return view('customer.profile');
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
        return view('customer.dashboard');
    }
    
}
