<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

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
        return view('admin.dashboard');
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
        return view('admin.profile');
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
}