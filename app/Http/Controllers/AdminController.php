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
use App\Models\Feedback;
use App\Models\FeedbackReply;
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
            'password' => 'required|min:6|confirmed',
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

        $remember = $request->boolean('remember');
        if ($remember) {
            Auth::guard('admin')->setRememberDuration(60 * 24 * 7); // 7 days
        }

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $admin = Auth::guard('admin')->user();

            LoginLog::create([
                'user_id' => $admin->id,
                'user_type' => 'admin',
                'username' => $admin->username,
                'ip_address' => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 255),
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
            'user_agent' => substr($request->userAgent() ?? '', 0, 255),
            'login_at' => now(),
            'status' => 'failed',
        ]);

        return back(fallback: route('admin.login'))
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
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        $branchId = $admin->branch_id ?? null;

        $washingAndDryingRequests = ServiceRequest::when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->whereIn('status', ['washing', 'drying'])
            ->with(['customer', 'clothType', 'branch', 'washingMachine', 'dryerMachine'])
            ->orderBy('id', 'desc')
            ->get();
        foreach ($washingAndDryingRequests as $ac) {
            $ac->syncCycleProgress();
        }

        $requests = ServiceRequest::when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->with(['customer', 'clothType', 'branch', 'washingMachine', 'dryerMachine'])
            ->latest('id')
            ->take(6)
            ->get();

        $requestCount = ServiceRequest::when($branchId, fn ($q) => $q->where('branch_id', $branchId))->count();
        $pendingCount = ServiceRequest::when($branchId, fn ($q) => $q->where('branch_id', $branchId))->where('status', 'received')->count();
        $activeCount = ServiceRequest::when($branchId, fn ($q) => $q->where('branch_id', $branchId))->whereIn('status', ['received', 'washing', 'drying', 'ready_for_pickup', 'ready'])->count();
        $salesTotal = Sale::when($branchId, fn ($q) => $q->where('branch_id', $branchId))->sum('amount');

        $branchMachineStats = Branch::where('is_active', true)
            ->when(!$admin?->isSuperAdmin() && $branchId, fn ($q) => $q->where('id', $branchId))
            ->with(['machines' => function ($q) {
                $q->orderBy('type', 'asc')->orderBy('id', 'asc');
            }])
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($branch) {
                $washers = $branch->machines->where('type', 'washer');
                $dryers = $branch->machines->where('type', 'dryer');

                $washersTotal = $washers->count();
                $washersAvailable = $washers->where('status', 'available')->count();
                $washersInUse = $washers->where('status', 'in_use')->count();
                $washersMaintenance = $washers->where('status', 'maintenance')->count();

                $dryersTotal = $dryers->count();
                $dryersAvailable = $dryers->where('status', 'available')->count();
                $dryersInUse = $dryers->where('status', 'in_use')->count();
                $dryersMaintenance = $dryers->where('status', 'maintenance')->count();

                return [
                    'id' => $branch->id,
                    'name' => $branch->name,
                    'address' => $branch->address,
                    'washers' => [
                        'total' => $washersTotal,
                        'available' => $washersAvailable,
                        'in_use' => $washersInUse,
                        'maintenance' => $washersMaintenance,
                        'ratio' => "{$washersAvailable}/{$washersTotal}",
                    ],
                    'dryers' => [
                        'total' => $dryersTotal,
                        'available' => $dryersAvailable,
                        'in_use' => $dryersInUse,
                        'maintenance' => $dryersMaintenance,
                        'ratio' => "{$dryersAvailable}/{$dryersTotal}",
                    ],
                    'total_machines' => $washersTotal + $dryersTotal,
                    'total_available' => $washersAvailable + $dryersAvailable,
                    'total_in_use' => $washersInUse + $dryersInUse,
                ];
            });

        return view('admin.dashboard', [
            'requestCount' => $requestCount,
            'pendingCount' => $pendingCount,
            'activeCount' => $activeCount,
            'salesTotal' => $salesTotal,
            'requests' => $requests,
            'washingAndDryingRequests' => $washingAndDryingRequests,
            'branchMachineStats' => $branchMachineStats,
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
        $branches = Branch::with(['managers', 'machines', 'serviceRequests'])->get();

        return view('admin.profile', compact('branch', 'branches'));
    }

    public function settings()
    {
        $admin = Auth::guard('admin')->user();
        $branch = $admin->branch ?? Branch::first();
        $branches = Branch::with(['managers', 'machines', 'serviceRequests'])->get();
        $clothTypes = ClothType::with('pricingTiers')->get();
        $addons = Addon::all();
        $cycleTimes = CycleTime::orderBy('kg', 'asc')->get();

        return view('admin.settings', compact('branch', 'branches', 'clothTypes', 'addons', 'cycleTimes'));
    }

    public function branches(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->isSuperAdmin()) {
            abort(403, 'Unauthorized action. Only owners can manage branches.');
        }

        $branches = Branch::with(['managers', 'machines', 'serviceRequests'])->get();
        return view('admin.branches', compact('branches'));
    }

    public function storeBranch(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->isSuperAdmin()) {
            abort(403, 'Unauthorized action. Only owners can create branches.');
        }

        $request->validate([
            'branch_name' => 'required|string|max:150|unique:branches,name',
            'branch_address' => 'required|string|max:255',
            'manager_username' => 'required|string|max:50|unique:admins,username',
            'manager_password' => 'required|string|min:6|confirmed',
            'manager_phone' => 'nullable|string|max:20',
        ], [
            'branch_name.unique' => 'A branch with this name already exists.',
            'manager_username.unique' => 'This manager username is already taken. Please choose another username.',
            'manager_password.confirmed' => 'Manager password confirmation does not match.',
            'manager_password.min' => 'Manager password must be at least 6 characters.',
        ]);

        DB::transaction(function () use ($request) {
            $defaultWashBuffer = Branch::first()?->wash_buffer_minutes ?? 5;

            $branch = Branch::create([
                'name' => trim($request->branch_name),
                'address' => trim($request->branch_address),
                'is_active' => true,
                'wash_buffer_minutes' => $defaultWashBuffer,
                'dry_buffer_minutes' => 0,
            ]);

            Admin::create([
                'branch_id' => $branch->id,
                'username' => trim($request->manager_username),
                'phone' => trim($request->manager_phone ?? '') ?: null,
                'password' => Hash::make($request->manager_password),
                'role' => Admin::ROLE_ADMIN,
            ]);
        });

        return back()->with('success', "Branch '{$request->branch_name}' and Manager account '{$request->manager_username}' registered successfully!");
    }

    public function updateBranchDetails(Request $request, Branch $branch)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:150|unique:branches,name,' . $branch->id,
            'address' => 'required|string|max:255',
            'wash_buffer_minutes' => 'nullable|integer|min:0|max:120',
        ]);

        $branch->update([
            'name' => trim($data['name']),
            'address' => trim($data['address']),
            'wash_buffer_minutes' => $data['wash_buffer_minutes'] ?? $branch->wash_buffer_minutes,
            'dry_buffer_minutes' => 0,
        ]);

        return back()->with('success', "Branch '{$branch->name}' updated successfully.");
    }

    public function toggleBranchStatus(Branch $branch)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $branch->update(['is_active' => !$branch->is_active]);
        $status = $branch->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Branch '{$branch->name}' has been {$status}.");
    }

    public function destroyBranch(Branch $branch)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if there are active service requests
        $activeOrdersCount = $branch->serviceRequests()
            ->whereIn('status', ['pending', 'received', 'washing', 'drying', 'ready_for_pickup', 'ready'])
            ->count();

        if ($activeOrdersCount > 0) {
            return back()->with('error', "Cannot delete '{$branch->name}' because it has {$activeOrdersCount} active/in-progress order(s).");
        }

        $name = $branch->name;
        // Unlink admins for this branch
        $branch->admins()->update(['branch_id' => null]);
        $branch->delete();

        return back()->with('success', "Branch '{$name}' deleted successfully.");
    }

    public function storeBranchManager(Request $request, Branch $branch)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'username' => 'required|string|max:100|unique:admins,username',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'username.unique' => 'This manager username is already taken.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $manager = Admin::create([
            'branch_id' => $branch->id,
            'username' => trim($request->username),
            'phone' => trim($request->phone ?? '') ?: null,
            'password' => Hash::make($request->password),
            'role' => Admin::ROLE_ADMIN,
        ]);

        return back()->with('success', "Manager '@{$manager->username}' registered for {$branch->name} successfully.");
    }

    public function resetManagerPassword(Request $request, Admin $adminUser)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $adminUser->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', "Password for manager '@{$adminUser->username}' updated successfully.");
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->isSuperAdmin()) {
            abort(403, 'Unauthorized action. Managers cannot edit profile information.');
        }

        $data = $request->validate([
            'username' => 'sometimes|required|string|max:255|unique:admins,username,' . $admin->id,
            'phone' => 'nullable|string|max:50',
        ]);

        $adminUpdates = [];
        if (isset($data['username'])) {
            $adminUpdates['username'] = $data['username'];
        }
        if (array_key_exists('phone', $data)) {
            $adminUpdates['phone'] = $data['phone'];
        }
        if (!empty($adminUpdates)) {
            $admin->update($adminUpdates);
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
            'wash_buffer_minutes' => 'nullable|integer|min:0|max:120',
            'cycle_times' => 'nullable|array',
            'cycle_times.*.washing_time_minutes' => 'required|integer|min:1|max:300',
            'cycle_times.*.drying_time_minutes' => 'required|integer|min:1|max:300',
            'tiers' => 'nullable|array',
            'tiers.*.price' => 'required|numeric|min:0',
            'addons' => 'nullable|array',
            'addons.*.price' => 'required|numeric|min:0',
        ]);

        $admin = Auth::guard('admin')->user();
        $washBuffer = isset($data['wash_buffer_minutes']) && $data['wash_buffer_minutes'] !== null
            ? (int) $data['wash_buffer_minutes']
            : 5;

        if ($admin && $admin->branch) {
            $admin->branch->update([
                'wash_buffer_minutes' => $washBuffer,
                'dry_buffer_minutes' => 0,
            ]);
        } else {
            // Owner / Super Admin: updates default buffer minutes across all branches
            Branch::query()->update([
                'wash_buffer_minutes' => $washBuffer,
                'dry_buffer_minutes' => 0,
            ]);
        }

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
                $isActive = isset($tierData['is_active'])
                    ? ($tierData['is_active'] == '1' || $tierData['is_active'] == 'on' || $tierData['is_active'] === true)
                    : ($tier->is_active ?? true);
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

    public function serviceRequests(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $isOwner = $admin?->isSuperAdmin();

        $activeCycles = ServiceRequest::when($admin->branch_id, fn ($q) => $q->where('branch_id', $admin->branch_id))
            ->whereIn('status', ['washing', 'drying'])
            ->get();
        foreach ($activeCycles as $ac) {
            $ac->syncCycleProgress();
        }

        // Check if explicit reset was requested
        if ($request->has('reset')) {
            session()->forget(['admin_sr_filter', 'admin_sr_sort', 'admin_sr_branch']);
            return redirect()->route('admin.service-requests');
        }

        // Check if query params were passed in the URL
        $hasFilterParam = $request->has('filter');
        $hasSortParam = $request->has('sort');
        $hasBranchParam = $request->has('branch');

        if ($hasFilterParam || $hasSortParam || $hasBranchParam) {
            // User explicitly navigated with filter/sort params -> save to session
            if ($hasFilterParam) {
                session(['admin_sr_filter' => $request->get('filter')]);
            }
            if ($hasSortParam) {
                session(['admin_sr_sort' => $request->get('sort')]);
            }
            if ($hasBranchParam) {
                $bVal = $request->get('branch');
                session(['admin_sr_branch' => ($bVal === 'all' || empty($bVal)) ? null : $bVal]);
            }
        } else {
            // No params in URL -> check if session has remembered filter/sort
            $savedFilter = session('admin_sr_filter');
            $savedSort = session('admin_sr_sort');
            $savedBranch = session('admin_sr_branch');

            $params = [];
            if (!empty($savedFilter) && $savedFilter !== 'all') {
                $params['filter'] = $savedFilter;
            }
            if (!empty($savedSort) && $savedSort !== 'newest') {
                $params['sort'] = $savedSort;
            }
            if (!empty($savedBranch) && $isOwner) {
                $params['branch'] = $savedBranch;
            }

            if (!empty($params)) {
                return redirect()->route('admin.service-requests', $params);
            }
        }

        $sort = $request->get('sort', session('admin_sr_sort', 'newest'));
        $filter = $request->get('filter', session('admin_sr_filter', 'all'));
        $branchFilter = $request->get('branch', session('admin_sr_branch'));
        if ($branchFilter === 'all') {
            $branchFilter = null;
        }

        $query = ServiceRequest::with(['customer', 'clothType', 'pricingTier', 'addons', 'branch', 'washingMachine', 'dryerMachine'])
            ->when($admin->branch_id, fn ($q) => $q->where('service_requests.branch_id', $admin->branch_id))
            ->when($isOwner && $branchFilter, fn ($q) => $q->where('service_requests.branch_id', $branchFilter))
            ->leftJoin('customers', 'service_requests.customer_id', '=', 'customers.id')
            ->leftJoin('cloth_types', 'service_requests.cloth_type_id', '=', 'cloth_types.id')
            ->leftJoin('branches', 'service_requests.branch_id', '=', 'branches.id')
            ->select('service_requests.*');

        match ($sort) {
            'oldest' => $query->orderBy('service_requests.id', 'asc'),
            'name_asc' => $query->orderBy(DB::raw("LOWER(COALESCE(customers.username, 'Walk-in'))"), 'asc')->orderBy('service_requests.id', 'desc'),
            'name_desc' => $query->orderBy(DB::raw("LOWER(COALESCE(customers.username, 'Walk-in'))"), 'desc')->orderBy('service_requests.id', 'desc'),
            'weight_desc' => $query->orderBy('service_requests.estimated_weight', 'desc')->orderBy('service_requests.id', 'desc'),
            'weight_asc' => $query->orderBy('service_requests.estimated_weight', 'asc')->orderBy('service_requests.id', 'desc'),
            'id_asc' => $query->orderBy('service_requests.id', 'asc'),
            'id_desc' => $query->orderBy('service_requests.id', 'desc'),
            'items_asc', 'quantity_asc' => $query->orderBy('service_requests.quantity', 'asc')->orderBy('service_requests.id', 'desc'),
            'items_desc', 'quantity_desc' => $query->orderBy('service_requests.quantity', 'desc')->orderBy('service_requests.id', 'desc'),
            'cloth_type_asc' => $query->orderBy('cloth_types.name', 'asc')->orderBy('service_requests.id', 'desc'),
            'cloth_type_desc' => $query->orderBy('cloth_types.name', 'desc')->orderBy('service_requests.id', 'desc'),
            'branch_asc' => $query->orderBy('branches.name', 'asc')->orderBy('service_requests.id', 'desc'),
            'branch_desc' => $query->orderBy('branches.name', 'desc')->orderBy('service_requests.id', 'desc'),
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

        $branches = $isOwner ? Branch::where('is_active', true)->get() : collect();

        return view('admin.service-requests', [
            'requests' => $requests,
            'allRequests' => $allRequests,
            'filter' => $filter,
            'sort' => $sort,
            'branches' => $branches,
            'branchFilter' => $branchFilter,
        ]);
    }

    public function createServiceRequest()
    {
        if (Auth::guard('admin')->user()?->isSuperAdmin()) {
            return redirect()->route('admin.service-requests')->with('error', 'Store owners have read-only access. Only branch managers can create service requests.');
        }

        return view('admin.service-request-create', [
            'branches' => Branch::where('is_active', true)->get(),
            'clothTypes' => ClothType::where('is_active', true)->with('pricingTiers')->get(),
            'addons' => Addon::all(),
            'customers' => Customer::where('username', 'not like', 'Walk-in%')
                ->where('username', 'not like', '%(Walk-in%')
                ->orderBy('username', 'asc')
                ->get(),
        ]);
    }

    public function storeWalkInServiceRequest(Request $request)
    {
        if (Auth::guard('admin')->user()?->isSuperAdmin()) {
            return redirect()->route('admin.service-requests')->with('error', 'Store owners have read-only access. Only branch managers can create service requests.');
        }

        $adminBranchId = auth('admin')->user()->branch_id ?? Branch::first()->id;
        $maxWeight = ($request->input('cloth_type_id') == 2) ? 5 : 8;

        $data = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'cloth_type_id' => 'required|exists:cloth_types,id',
            'pricing_tier_id' => 'required|exists:pricing_tiers,id',
            'estimated_weight' => 'nullable|numeric|min:0|max:' . $maxWeight,
            'quantity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:1000',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:addons,id',
            'items' => 'nullable|array',
            'items.*' => 'nullable|integer|min:0|max:500',
            'custom_items' => 'nullable|array',
            'custom_items.*.name' => 'nullable|string|max:100',
            'custom_items.*.qty' => 'nullable|integer|min:1|max:500',
        ], [
            'estimated_weight.max' => "Weight cannot exceed {$maxWeight} kg for this item category.",
            'estimated_weight.min' => "Weight cannot be negative.",
        ]);

        $branchId = $request->input('branch_id', $adminBranchId);
        $clothType = ClothType::findOrFail($data['cloth_type_id']);
        $tier = PricingTier::where('id', $data['pricing_tier_id'])
            ->where('cloth_type_id', $data['cloth_type_id'])
            ->firstOrFail();

        // Process itemized breakdown if provided
        $totalItemCount = 0;
        $itemsToCreate = [];

        if (!$clothType->requires_size) {
            if (!empty($data['items']) && is_array($data['items'])) {
                foreach (ServiceRequest::PRESET_CLOTH_ITEMS as $key => $label) {
                    $qty = isset($data['items'][$key]) ? (int)$data['items'][$key] : 0;
                    if ($qty > 0) {
                        $totalItemCount += $qty;
                        $itemsToCreate[] = [
                            'item_name' => $label,
                            'is_custom' => false,
                            'declared_qty' => $qty,
                            'verified_qty' => $qty,
                        ];
                    }
                }
            }

            if (!empty($data['custom_items']) && is_array($data['custom_items'])) {
                foreach ($data['custom_items'] as $cItem) {
                    $name = trim($cItem['name'] ?? '');
                    $qty = isset($cItem['qty']) ? (int)$cItem['qty'] : 0;
                    if ($name !== '' && $qty > 0) {
                        $totalItemCount += $qty;
                        $itemsToCreate[] = [
                            'item_name' => $name,
                            'is_custom' => true,
                            'declared_qty' => $qty,
                            'verified_qty' => $qty,
                        ];
                    }
                }
            }
        }

        $comforterSize = null;
        if ($clothType->requires_size || !empty($tier->size)) {
            $quantity = !empty($data['quantity']) ? (int)$data['quantity'] : 1;
            $estimatedWeight = !empty($data['estimated_weight']) ? (float)$data['estimated_weight'] : null;
            $comforterSize = $tier->size ?? $tier->name;
            $basePrice = $tier->price * $quantity;
        } else {
            $quantity = ($totalItemCount > 0) ? $totalItemCount : (!empty($data['quantity']) ? (int)$data['quantity'] : 1);
            $rawWeight = isset($data['estimated_weight']) ? (float)$data['estimated_weight'] : 0;
            $estimatedWeight = ($rawWeight > 0) ? $rawWeight : ($tier->min_weight ?? 1);
            $comforterSize = null;
            $basePrice = $tier->price;
        }

        $addons = \App\Models\Addon::whereIn('id', $data['addon_ids'] ?? [])->where('is_active', true)->get();

        $rawCustomerName = trim($data['customer_name'] ?? '');
        $isWalkIn = empty($rawCustomerName) || strcasecmp($rawCustomerName, 'walk-in') === 0 || strcasecmp($rawCustomerName, 'walkin') === 0;

        if ($isWalkIn) {
            $customer = Customer::create([
                'username' => 'Walk-in (' . now()->format('M d, h:i A') . ')',
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make(Str::random(32)),
                'branch_id' => $branchId
            ]);
        } else {
            $existingCustomer = Customer::where('username', $rawCustomerName)->first();
            if ($existingCustomer) {
                $customer = $existingCustomer;
                if (!empty($data['phone']) && empty($customer->phone)) {
                    $customer->update(['phone' => $data['phone']]);
                }
            } else {
                $customer = Customer::create([
                    'username' => $rawCustomerName . ' (Walk-in)',
                    'phone' => $data['phone'] ?? null,
                    'password' => Hash::make(Str::random(32)),
                    'branch_id' => $branchId
                ]);
            }
        }

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

        if (!empty($itemsToCreate)) {
            foreach ($itemsToCreate as $itemData) {
                $serviceRequest->items()->create($itemData);
            }
        }

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
        $serviceRequest->syncCycleProgress();
        $serviceRequest->load(['customer', 'branch', 'clothType', 'pricingTier', 'addons', 'statusHistories', 'washingMachine', 'dryerMachine', 'items']);
        
        $branchId = $serviceRequest->branch_id;
        $washers = Machine::where('branch_id', $branchId)
            ->where('type', 'washer')
            ->orderByRaw('CAST(REGEXP_SUBSTR(name, "[0-9]+") AS UNSIGNED) ASC, id ASC')
            ->get();

        $dryers = Machine::where('branch_id', $branchId)
            ->where('type', 'dryer')
            ->orderByRaw('CAST(REGEXP_SUBSTR(name, "[0-9]+") AS UNSIGNED) ASC, id ASC')
            ->get();

        $tiers = PricingTier::where('cloth_type_id', $serviceRequest->cloth_type_id)->where('is_active', true)->get();
        return view('admin.service-request-details', compact('serviceRequest', 'washers', 'dryers', 'tiers'));
    }

    public function acceptServiceRequest(ServiceRequest $serviceRequest)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin?->isSuperAdmin()) {
            return back()->with('error', 'Store owners have read-only access. Only branch managers can update request status.');
        }
        if ($serviceRequest->final_price === null) {
            return back()->withErrors(['status' => 'Please verify the laundry items and scale weight before accepting this request.']);
        }
        $serviceRequest->update(['admin_id' => $admin->id, 'accepted_by' => $admin->id, 'status' => 'received']);
        ServiceRequestStatusHistory::create(['service_request_id' => $serviceRequest->id, 'status' => 'received', 'changed_by' => $admin->id, 'started_at' => now()]);
        return back()->with('success', 'Service request accepted.');
    }

    public function updateServiceRequestStatus(Request $request, ServiceRequest $serviceRequest)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin?->isSuperAdmin()) {
            return back()->with('error', 'Store owners have read-only access. Only branch managers can update request status.');
        }

        $data = $request->validate([
            'status' => 'required|in:pending,received,washing,drying,ready_for_pickup,completed,cancelled',
            'washing_machine_id' => 'nullable|exists:machines,id',
            'dryer_machine_id' => 'nullable|exists:machines,id',
        ]);

        $targetStatus = $data['status'];

        if ($serviceRequest->status === 'completed' && $targetStatus === 'cancelled') {
            return back()->withErrors(['status' => 'A completed request cannot be cancelled.']);
        }
        if ($serviceRequest->status === 'pending' && $targetStatus !== 'cancelled' && $serviceRequest->final_price === null) {
            return back()->withErrors(['status' => 'Please verify the laundry items and scale weight before accepting or advancing this request.']);
        }
        if ($targetStatus === 'completed' && $serviceRequest->final_price === null) {
            return back()->withErrors(['status' => 'Verify the laundry and calculate the final price before completing this request.']);
        }

        // 1. Strict Washer Validation
        if (!empty($data['washing_machine_id'])) {
            $washer = Machine::find($data['washing_machine_id']);
            if (!$washer || $washer->type !== 'washer' || $washer->branch_id != $serviceRequest->branch_id) {
                return back()->with('error', 'Selected washing machine is invalid for this branch.');
            }
            if ($washer->status === 'maintenance') {
                return back()->with('error', "Cannot assign '{$washer->name}' because it is currently under maintenance.");
            }
            if ($washer->status === 'in_use' && $serviceRequest->washing_machine_id != $washer->id) {
                return back()->with('error', "Cannot assign '{$washer->name}' because it is currently in use with another active order.");
            }
        }

        // 2. Strict Dryer Validation
        if (!empty($data['dryer_machine_id'])) {
            $dryer = Machine::find($data['dryer_machine_id']);
            if (!$dryer || $dryer->type !== 'dryer' || $dryer->branch_id != $serviceRequest->branch_id) {
                return back()->with('error', 'Selected dryer machine is invalid for this branch.');
            }
            if ($dryer->status === 'maintenance') {
                return back()->with('error', "Cannot assign '{$dryer->name}' because it is currently under maintenance.");
            }
            if ($dryer->status === 'in_use' && $serviceRequest->dryer_machine_id != $dryer->id) {
                return back()->with('error', "Cannot assign '{$dryer->name}' because it is currently in use with another active order.");
            }
        }

        // Auto-assign or validate washing machine if jumping to washing
        if ($targetStatus === 'washing') {
            if (empty($data['washing_machine_id'])) {
                if ($serviceRequest->washing_machine_id) {
                    $currWasher = Machine::find($serviceRequest->washing_machine_id);
                    if ($currWasher && $currWasher->status === 'maintenance') {
                        return back()->with('error', "Machine '{$currWasher->name}' is currently under maintenance. Please select an available washer.");
                    }
                    $data['washing_machine_id'] = $serviceRequest->washing_machine_id;
                } else {
                    $availableWasher = Machine::where('branch_id', $serviceRequest->branch_id)
                        ->where('type', 'washer')
                        ->where('status', 'available')
                        ->orderByRaw('CAST(REGEXP_SUBSTR(name, "[0-9]+") AS UNSIGNED) ASC, id ASC')
                        ->first();

                    if (!$availableWasher) {
                        return back()->with('error', 'No available washing machine found in this branch (all units are in use or under maintenance).');
                    }

                    $data['washing_machine_id'] = $availableWasher->id;
                }
            }
        }

        // Auto-assign or validate dryer machine if jumping to drying
        if ($targetStatus === 'drying') {
            if (empty($data['dryer_machine_id'])) {
                if ($serviceRequest->dryer_machine_id) {
                    $currDryer = Machine::find($serviceRequest->dryer_machine_id);
                    if ($currDryer && $currDryer->status === 'maintenance') {
                        return back()->with('error', "Machine '{$currDryer->name}' is currently under maintenance. Please select an available dryer.");
                    }
                    $data['dryer_machine_id'] = $serviceRequest->dryer_machine_id;
                } else {
                    $availableDryer = Machine::where('branch_id', $serviceRequest->branch_id)
                        ->where('type', 'dryer')
                        ->where('status', 'available')
                        ->orderByRaw('CAST(REGEXP_SUBSTR(name, "[0-9]+") AS UNSIGNED) ASC, id ASC')
                        ->first();

                    if (!$availableDryer) {
                        return back()->with('error', 'No available dryer machine found in this branch (all units are in use or under maintenance).');
                    }

                    $data['dryer_machine_id'] = $availableDryer->id;
                }
            }
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

            // Cycle Timer Setup
            $washBuffer = $serviceRequest->branch?->wash_buffer_minutes ?? 5;
            $setting = $serviceRequest->getCycleSetting();
            $washMins = $setting ? $setting->washing_time_minutes : 33;
            $dryMins = $setting ? $setting->drying_time_minutes : 25;
            if ($washMins < 1) $washMins = 33;
            if ($dryMins < 1) $dryMins = 25;

            if ($targetStatus === 'washing') {
                $updatePayload['wash_started_at'] = now();
                $updatePayload['wash_duration_minutes'] = $washMins;
                $updatePayload['rest_duration_minutes'] = $washBuffer;
                $updatePayload['dry_duration_minutes'] = $dryMins;
                $updatePayload['dry_rest_duration_minutes'] = 0;
                $updatePayload['dry_started_at'] = null; // Clear so drying will cleanly start fresh later!
                $updatePayload['cycle_phase'] = 'washing';
                $updatePayload['cycle_paused_at'] = null;
                $updatePayload['paused_remaining_seconds'] = null;
            } elseif ($targetStatus === 'drying') {
                // If entering drying, always cleanly start drying timer now!
                $updatePayload['dry_started_at'] = now();
                $updatePayload['dry_duration_minutes'] = $dryMins;
                $updatePayload['dry_rest_duration_minutes'] = 0;
                $updatePayload['cycle_phase'] = 'drying';
                $updatePayload['cycle_paused_at'] = null;
                $updatePayload['paused_remaining_seconds'] = null;
            } elseif (in_array($targetStatus, ['pending', 'received'])) {
                $updatePayload['wash_started_at'] = null;
                $updatePayload['dry_started_at'] = null;
                $updatePayload['cycle_phase'] = 'idle';
                $updatePayload['cycle_paused_at'] = null;
                $updatePayload['paused_remaining_seconds'] = null;
            } elseif (in_array($targetStatus, ['ready_for_pickup', 'ready', 'completed', 'cancelled'])) {
                $updatePayload['cycle_phase'] = 'completed';
                $updatePayload['cycle_paused_at'] = null;
                $updatePayload['paused_remaining_seconds'] = null;
            }

            if (!empty($data['washing_machine_id'])) {
                if ($serviceRequest->washing_machine_id && $serviceRequest->washing_machine_id != $data['washing_machine_id']) {
                    Machine::where('id', $serviceRequest->washing_machine_id)
                        ->where('status', '!=', 'maintenance')
                        ->update(['status' => 'available']);
                }
                $updatePayload['washing_machine_id'] = $data['washing_machine_id'];
                Machine::where('id', $data['washing_machine_id'])->update(['status' => 'in_use']);
            }

            if (!empty($data['dryer_machine_id'])) {
                if ($serviceRequest->dryer_machine_id && $serviceRequest->dryer_machine_id != $data['dryer_machine_id']) {
                    Machine::where('id', $serviceRequest->dryer_machine_id)
                        ->where('status', '!=', 'maintenance')
                        ->update(['status' => 'available']);
                }
                $updatePayload['dryer_machine_id'] = $data['dryer_machine_id'];
                Machine::where('id', $data['dryer_machine_id'])->update(['status' => 'in_use']);

                if ($serviceRequest->washing_machine_id) {
                    Machine::where('id', $serviceRequest->washing_machine_id)
                        ->where('status', '!=', 'maintenance')
                        ->update(['status' => 'available']);
                }
            }

            if (in_array($targetStatus, ['ready_for_pickup', 'ready', 'completed', 'cancelled'])) {
                if ($serviceRequest->dryer_machine_id) {
                    Machine::where('id', $serviceRequest->dryer_machine_id)
                        ->where('status', '!=', 'maintenance')
                        ->update(['status' => 'available']);
                }
                if ($serviceRequest->washing_machine_id) {
                    Machine::where('id', $serviceRequest->washing_machine_id)
                        ->where('status', '!=', 'maintenance')
                        ->update(['status' => 'available']);
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
            if ($m) $msg = "Moved to Washing in {$m->name} (Timer: {$serviceRequest->wash_duration_minutes}m).";
        } elseif ($targetStatus === 'drying' && !empty($data['dryer_machine_id'])) {
            $m = Machine::find($data['dryer_machine_id']);
            if ($m) $msg = "Moved to Drying in {$m->name} (Timer: {$serviceRequest->dry_duration_minutes}m).";
        }

        return back()->with('success', $msg);
    }

    public function cycleSync(ServiceRequest $serviceRequest)
    {
        $changed = $serviceRequest->syncCycleProgress();
        $serviceRequest->refresh();
        $info = $serviceRequest->cycle_info;

        return response()->json([
            'success' => true,
            'changed' => $changed,
            'status' => $serviceRequest->status,
            'cycle_phase' => $serviceRequest->cycle_phase,
            'weight_verification_status' => $serviceRequest->weight_verification_status,
            'actual_weight' => $serviceRequest->actual_weight,
            'final_price' => $serviceRequest->final_price ?? $serviceRequest->estimated_price,
            'info' => $info,
        ]);
    }

    public function cycleAction(Request $request, ServiceRequest $serviceRequest)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin?->isSuperAdmin()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Store owners have read-only access.'], 403);
            }
            return back()->with('error', 'Store owners have read-only access. Only branch managers can modify active cycles.');
        }

        $data = $request->validate([
            'action' => 'required|in:skip_wash,skip_rest,skip_dry,skip_dry_buffer,finish_folding,make_ready,pause,resume,add_time',
        ]);

        $now = now();
        $info = $serviceRequest->cycle_info;

        switch ($data['action']) {
            case 'skip_wash':
                if ($serviceRequest->status === 'washing') {
                    // Advance to Wash Buffer: clothes are still in the washer drum! Washer remains in_use!
                    if ($serviceRequest->washing_machine_id) {
                        Machine::where('id', $serviceRequest->washing_machine_id)->update(['status' => 'in_use']);
                    }
                    $serviceRequest->wash_started_at = $now->copy()->subMinutes($serviceRequest->wash_duration_minutes ?: 33);
                    $serviceRequest->cycle_phase = 'resting';
                    $serviceRequest->cycle_paused_at = null;
                    $serviceRequest->paused_remaining_seconds = null;
                    $serviceRequest->save();
                }
                break;

            case 'skip_rest':
                if ($serviceRequest->status === 'washing') {
                    if ($serviceRequest->washing_machine_id) {
                        Machine::where('id', $serviceRequest->washing_machine_id)
                            ->where('status', '!=', 'maintenance')
                            ->update(['status' => 'available']);
                    }

                    $availableDryer = Machine::where('branch_id', $serviceRequest->branch_id)
                        ->where('type', 'dryer')
                        ->where('status', 'available')
                        ->orderByRaw('CAST(REGEXP_SUBSTR(name, "[0-9]+") AS UNSIGNED) ASC, id ASC')
                        ->first();

                    if (!$availableDryer) {
                        return back()->with('error', 'No dryer machine is currently available in this branch. Please free a dryer first.');
                    }

                    $setting = $serviceRequest->getCycleSetting();
                    $dryMins = $setting ? $setting->drying_time_minutes : 25;

                    $serviceRequest->status = 'drying';
                    $serviceRequest->cycle_phase = 'drying';
                    $serviceRequest->dryer_machine_id = $availableDryer->id;
                    $serviceRequest->dry_started_at = $now;
                    $serviceRequest->dry_duration_minutes = $dryMins;
                    $serviceRequest->dry_rest_duration_minutes = 0;
                    $serviceRequest->cycle_paused_at = null;
                    $serviceRequest->paused_remaining_seconds = null;
                    $serviceRequest->save();

                    $availableDryer->update(['status' => 'in_use']);

                    $current = $serviceRequest->statusHistories()->whereNull('ended_at')->latest()->first();
                    if ($current) $current->update(['ended_at' => $now]);

                    ServiceRequestStatusHistory::create([
                        'service_request_id' => $serviceRequest->id,
                        'status' => 'drying',
                        'changed_by' => $admin ? $admin->id : 1,
                        'started_at' => $now,
                    ]);
                }
                break;

            case 'skip_dry':
            case 'skip_dry_buffer':
                if ($serviceRequest->status === 'drying') {
                    if ($serviceRequest->dryer_machine_id) {
                        Machine::where('id', $serviceRequest->dryer_machine_id)
                            ->where('status', '!=', 'maintenance')
                            ->update(['status' => 'available']);
                    }
                    $setting = $serviceRequest->getCycleSetting();
                    $dryMins = $serviceRequest->dry_duration_minutes ?: ($setting ? $setting->drying_time_minutes : 25);

                    // Advance directly into Folding
                    $serviceRequest->dry_started_at = $now->copy()->subMinutes($dryMins);
                    $serviceRequest->cycle_phase = 'folding';
                    $serviceRequest->cycle_paused_at = null;
                    $serviceRequest->paused_remaining_seconds = null;
                    $serviceRequest->save();
                }
                break;

            case 'finish_folding':
            case 'make_ready':
                if ($serviceRequest->status === 'drying') {
                    if ($serviceRequest->dryer_machine_id) {
                        Machine::where('id', $serviceRequest->dryer_machine_id)
                            ->where('status', '!=', 'maintenance')
                            ->update(['status' => 'available']);
                    }
                    $serviceRequest->status = 'ready_for_pickup';
                    $serviceRequest->cycle_phase = 'completed';
                    $serviceRequest->cycle_paused_at = null;
                    $serviceRequest->paused_remaining_seconds = null;
                    $serviceRequest->save();

                    $current = $serviceRequest->statusHistories()->whereNull('ended_at')->latest()->first();
                    if ($current) $current->update(['ended_at' => $now]);

                    ServiceRequestStatusHistory::create([
                        'service_request_id' => $serviceRequest->id,
                        'status' => 'ready_for_pickup',
                        'changed_by' => $admin ? $admin->id : 1,
                        'started_at' => $now,
                    ]);
                }
                break;

            case 'pause':
                if ($serviceRequest->cycle_phase === 'folding') {
                    break;
                }
                if (!$serviceRequest->cycle_paused_at) {
                    $serviceRequest->cycle_paused_at = $now;
                    $serviceRequest->paused_remaining_seconds = $info['remaining_seconds'];
                    $serviceRequest->save();
                }
                break;

            case 'resume':
                if ($serviceRequest->cycle_paused_at && $serviceRequest->cycle_phase !== 'folding') {
                    $setting = $serviceRequest->getCycleSetting();
                    $remSec = $serviceRequest->paused_remaining_seconds !== null 
                        ? max(0, $serviceRequest->paused_remaining_seconds) 
                        : max(0, $info['remaining_seconds']);

                    if ($serviceRequest->status === 'washing') {
                        $washMins = $serviceRequest->wash_duration_minutes ?: ($setting ? $setting->washing_time_minutes : 33);
                        $restMins = $serviceRequest->rest_duration_minutes ?: ($serviceRequest->branch?->wash_buffer_minutes ?? 5);
                        if ($serviceRequest->cycle_phase === 'resting') {
                            $totalSec = $restMins * 60;
                            $elapsedSec = max(0, $totalSec - $remSec);
                            $serviceRequest->wash_started_at = $now->copy()->subMinutes($washMins)->subSeconds($elapsedSec);
                        } else {
                            $totalSec = $washMins * 60;
                            $elapsedSec = max(0, $totalSec - $remSec);
                            $serviceRequest->wash_started_at = $now->copy()->subSeconds($elapsedSec);
                        }
                    } elseif ($serviceRequest->status === 'drying') {
                        $dryMins = $serviceRequest->dry_duration_minutes ?: ($setting ? $setting->drying_time_minutes : 25);
                        $totalSec = $dryMins * 60;
                        $elapsedSec = max(0, $totalSec - $remSec);
                        $serviceRequest->dry_started_at = $now->copy()->subSeconds($elapsedSec);
                        $serviceRequest->dry_duration_minutes = $dryMins;
                    }
                    $serviceRequest->cycle_paused_at = null;
                    $serviceRequest->paused_remaining_seconds = null;
                    $serviceRequest->save();
                }
                break;

            case 'add_time':
                if ($serviceRequest->status === 'washing') {
                    if ($serviceRequest->cycle_phase === 'resting') {
                        $serviceRequest->increment('rest_duration_minutes', 5);
                    } else {
                        $serviceRequest->increment('wash_duration_minutes', 5);
                    }
                } elseif ($serviceRequest->status === 'drying' && $serviceRequest->cycle_phase !== 'folding') {
                    $serviceRequest->increment('dry_duration_minutes', 5);
                }
                break;
        }

        if ($request->wantsJson()) {
            $serviceRequest->refresh();
            return response()->json([
                'success' => true,
                'status' => $serviceRequest->status,
                'info' => $serviceRequest->cycle_info,
            ]);
        }

        if (in_array($data['action'], ['skip_dry', 'skip_dry_buffer'])) {
            return back()->with('success', 'Drying completed! Now folding and packaging clothes (Dryer is now available).');
        }
        if (in_array($data['action'], ['finish_folding', 'make_ready'])) {
            return back()->with('success', 'Folding completed! Order is now ready for pickup.');
        }

        return back()->with('success', 'Cycle timer updated.');
    }

    public function updateServiceRequestDetails(Request $request, ServiceRequest $serviceRequest)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin?->isSuperAdmin()) {
            return back()->with('error', 'Store owners have read-only access. Only branch managers can verify and update request details.');
        }

        $clothType = $serviceRequest->clothType;

        // 1. Comforters: Verify comforter size (sets price) AND scale weight (sets wash/dry cycle timer)
        if ($clothType->requires_size) {
            $data = $request->validate([
                'pricing_tier_id' => 'required|exists:pricing_tiers,id',
                'actual_weight' => 'required|numeric|min:1|max:8',
                'actual_quantity' => 'nullable|integer|min:1',
                'verified_items' => 'nullable|array',
                'verified_items.*' => 'nullable|integer|min:0',
                'new_items' => 'nullable|array',
                'new_items.*.name' => 'nullable|string|max:100',
                'new_items.*.qty' => 'nullable|integer|min:1|max:500',
                'delete_items' => 'nullable|array',
                'delete_items.*' => 'integer|exists:service_request_items,id',
                'verification_note_custom' => 'nullable|string|max:500',
            ], [
                'actual_weight.required' => 'Please weigh the comforter on the scale and enter the weight.',
                'actual_weight.max' => 'Comforter scale weight cannot exceed 8 kg.',
                'actual_weight.min' => 'Scale weight must be at least 1 kg.',
            ]);

            $tier = PricingTier::where('id', $data['pricing_tier_id'])
                ->where('cloth_type_id', $serviceRequest->cloth_type_id)
                ->firstOrFail();

            $verifiedQuantity = !empty($data['actual_quantity']) ? (int)$data['actual_quantity'] : 1;
            $basePrice = $tier->price * $verifiedQuantity;
            $addonTotal = $serviceRequest->addons()->get()->sum(fn ($addon) => $addon->pivot->price ?? $addon->price);
            $finalPrice = $basePrice + $addonTotal;
            $actualWeight = (float)$data['actual_weight'];

            // Handle deletion of existing items removed by staff
            if (!empty($data['delete_items']) && is_array($data['delete_items'])) {
                $serviceRequest->items()->whereIn('id', $data['delete_items'])->delete();
                $serviceRequest->load('items');
            }

            // Update itemized counts if present
            if (!empty($data['verified_items']) && is_array($data['verified_items'])) {
                foreach ($serviceRequest->items as $item) {
                    if (isset($data['verified_items'][$item->id])) {
                        $item->update(['verified_qty' => max(0, (int)$data['verified_items'][$item->id])]);
                    }
                }
            }

            // Process newly added other / discovered clothes
            if (!empty($data['new_items']) && is_array($data['new_items'])) {
                foreach ($data['new_items'] as $newRow) {
                    $name = isset($newRow['name']) ? trim($newRow['name']) : '';
                    $qty = isset($newRow['qty']) ? (int)$newRow['qty'] : 0;
                    if ($name !== '' && $qty > 0) {
                        $serviceRequest->items()->create([
                            'item_name' => $name,
                            'declared_qty' => 0,
                            'verified_qty' => $qty,
                            'is_custom' => true,
                        ]);
                    }
                }
            }

            $isPending = ($serviceRequest->status === 'pending');
            $newStatus = $isPending ? 'received' : $serviceRequest->status;
            $vNote = "Comforter verified: {$tier->name} with scale weight {$actualWeight} kg.";
            if (!empty($data['verification_note_custom'])) {
                $vNote .= " Note: " . trim($data['verification_note_custom']);
            }

            $serviceRequest->update([
                'pricing_tier_id' => $tier->id,
                'comforter_size' => $tier->size ?? $tier->name,
                'quantity' => $verifiedQuantity,
                'actual_quantity' => $verifiedQuantity,
                'actual_weight' => $actualWeight,
                'final_price' => $finalPrice,
                'status' => $newStatus,
                'admin_id' => $serviceRequest->admin_id ?? $admin?->id,
                'accepted_by' => $serviceRequest->accepted_by ?? $admin?->id,
                'weight_verification_status' => 'correct',
                'verification_note' => $vNote,
            ]);

            if ($isPending) {
                ServiceRequestStatusHistory::create([
                    'service_request_id' => $serviceRequest->id,
                    'status' => 'received',
                    'changed_by' => $admin?->id,
                    'started_at' => now(),
                ]);
            }

            $successMsg = $isPending
                ? "Comforter verified as " . ($tier->size ?? $tier->name) . " ({$actualWeight} kg, ₱" . number_format($finalPrice, 0) . "). Status updated to Received."
                : "Comforter verified and final price set to ₱" . number_format($finalPrice, 0) . ". You can now proceed.";

            return back()->with('success', $successMsg);
        }

        // 2. Weight-based types (Regular clothes, Beddings/Towels/Curtains)
        $maxWeight = ($serviceRequest->cloth_type_id == 2) ? 5 : 8;
        $data = $request->validate([
            'actual_weight' => 'required|numeric|min:1|max:' . $maxWeight,
            'actual_quantity' => 'required|integer|min:1',
            'verified_items' => 'nullable|array',
            'verified_items.*' => 'nullable|integer|min:0',
            'new_items' => 'nullable|array',
            'new_items.*.name' => 'nullable|string|max:100',
            'new_items.*.qty' => 'nullable|integer|min:1|max:500',
            'delete_items' => 'nullable|array',
            'delete_items.*' => 'integer|exists:service_request_items,id',
            'verification_note_custom' => 'nullable|string|max:500',
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

        // Handle deletion of existing items removed by staff
        if (!empty($data['delete_items']) && is_array($data['delete_items'])) {
            $serviceRequest->items()->whereIn('id', $data['delete_items'])->delete();
            $serviceRequest->load('items');
        }

        // Item-by-item verification
        $itemsDiscrepancies = [];
        $itemsMatched = true;
        $calculatedItemTotal = 0;

        if (!empty($data['verified_items']) && is_array($data['verified_items']) && $serviceRequest->items->count() > 0) {
            foreach ($serviceRequest->items as $item) {
                if (isset($data['verified_items'][$item->id])) {
                    $vQty = max(0, (int)$data['verified_items'][$item->id]);
                    $item->update(['verified_qty' => $vQty]);
                    $calculatedItemTotal += $vQty;
                    if ($vQty !== (int)$item->declared_qty) {
                        $itemsMatched = false;
                        $diff = $vQty - $item->declared_qty;
                        $diffLabel = $diff > 0 ? "+{$diff} extra" : "{$diff} missing";
                        $itemsDiscrepancies[] = "{$item->item_name} ({$vQty} pcs, {$diffLabel})";
                    }
                }
            }
        }

        // Process newly added other / discovered clothes
        if (!empty($data['new_items']) && is_array($data['new_items'])) {
            foreach ($data['new_items'] as $newRow) {
                $name = isset($newRow['name']) ? trim($newRow['name']) : '';
                $qty = isset($newRow['qty']) ? (int)$newRow['qty'] : 0;
                if ($name !== '' && $qty > 0) {
                    $serviceRequest->items()->create([
                        'item_name' => $name,
                        'declared_qty' => 0, // Not initially declared by customer
                        'verified_qty' => $qty,
                        'is_custom' => true,
                    ]);
                    $itemsMatched = false;
                    $itemsDiscrepancies[] = "{$name} (+{$qty} extra/discovered)";
                    $calculatedItemTotal += $qty;
                }
            }
        }

        if ($calculatedItemTotal > 0) {
            $verifiedQuantity = $calculatedItemTotal;
        }

        $basePrice = $tier->price;
        $finalPrice = $basePrice + $addonTotal;

        $submittedWeight = $serviceRequest->estimated_weight !== null ? (float)$serviceRequest->estimated_weight : null;
        $submittedQuantity = (int)$serviceRequest->quantity;
        $actualWeight = (float)$data['actual_weight'];
        $actualQuantity = $verifiedQuantity;

        $weightMatched = ($submittedWeight !== null) ? (abs($actualWeight - $submittedWeight) < 0.05) : true;
        $quantityMatched = ($actualQuantity === $submittedQuantity) && $itemsMatched;
        $isCorrect = $weightMatched && $quantityMatched;

        $isPending = ($serviceRequest->status === 'pending');
        $newStatus = $isPending ? 'received' : $serviceRequest->status;

        if ($isCorrect) {
            $verificationStatus = 'correct';
            $verificationNote = "Laundry weight ({$actualWeight} kg) and item count ({$actualQuantity} pcs) were verified as correct.";
            if (!empty($data['verification_note_custom'])) {
                $verificationNote .= " Staff note: " . trim($data['verification_note_custom']);
            }
            $flashMsg = $isPending
                ? "Laundry verified as CORRECT ({$actualWeight} kg, {$actualQuantity} pcs). Status automatically updated to Received."
                : "Laundry verified as CORRECT ({$actualWeight} kg, {$actualQuantity} pcs). Final price set to PHP " . number_format($finalPrice, 0) . ".";
        } else {
            $verificationStatus = 'incorrect';
            $adjustments = [];
            if (!$weightMatched) {
                $adjustments[] = "Weight: {$actualWeight} kg (submitted: " . ($submittedWeight !== null ? "{$submittedWeight} kg" : "None") . ")";
            }
            if (!$quantityMatched) {
                if (!empty($itemsDiscrepancies)) {
                    $adjustments[] = "Items breakdown adjusted: " . implode('; ', $itemsDiscrepancies);
                } else {
                    $adjustments[] = "Items: {$actualQuantity} pcs (submitted: {$submittedQuantity} pcs)";
                }
            }
            $verificationNote = "Laundry details updated by staff during weigh-in: " . implode(' · ', $adjustments) . ". Final price: PHP " . number_format($finalPrice, 0) . ".";
            if (!empty($data['verification_note_custom'])) {
                $verificationNote .= " Staff note: " . trim($data['verification_note_custom']);
            }
            $flashMsg = $isPending
                ? "Laundry verified with adjustments. Customer notified and status updated to Received."
                : "Laundry verified with adjustments. Final price set to PHP " . number_format($finalPrice, 0) . ".";
        }

        $serviceRequest->update([
            'pricing_tier_id' => $data['pricing_tier_id'] ?? $tier->id,
            'actual_weight' => $actualWeight,
            'actual_quantity' => $actualQuantity,
            'final_price' => $finalPrice,
            'weight_verification_status' => $verificationStatus,
            'verification_note' => $verificationNote,
            'status' => $newStatus,
            'admin_id' => $serviceRequest->admin_id ?? $admin?->id,
            'accepted_by' => $serviceRequest->accepted_by ?? $admin?->id,
        ]);

        if ($isPending) {
            ServiceRequestStatusHistory::create([
                'service_request_id' => $serviceRequest->id,
                'status' => 'received',
                'changed_by' => $admin?->id,
                'started_at' => now(),
            ]);
        }

        return back()->with('success', $flashMsg);
    }

    public function machines()
    {
        $admin = Auth::guard('admin')->user();

        $activeCycles = ServiceRequest::when($admin->branch_id, fn ($q) => $q->where('branch_id', $admin->branch_id))
            ->whereIn('status', ['washing', 'drying'])
            ->get();
        foreach ($activeCycles as $ac) {
            $ac->syncCycleProgress();
        }

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
        $isOwner = $admin && $admin->isSuperAdmin();

        if (!$isOwner) {
            abort(403, 'Only owners can add new machines.');
        }

        $rules = [
            'type' => 'required|in:washer,dryer',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'name' => 'nullable|string|max:100',
        ];

        if ($isOwner) {
            $rules['branch_id'] = 'required|exists:branches,id';
        }

        $data = $request->validate($rules, [
            'branch_id.required' => 'Please select the branch where this machine will be installed.',
            'branch_id.exists' => 'The selected branch is invalid.',
        ]);

        $branchId = (int)$data['branch_id'];

        $typeRaw = $request->type;
        $typeLabel = ucfirst($typeRaw);
        $brand = trim($request->brand ?? '');
        $model = trim($request->model ?? '');

        // Auto-generate sequential identifier based on target branch and type
        $branchMachines = Machine::where('branch_id', $branchId)->where('type', $typeRaw)->get();
        $maxNum = 0;
        foreach ($branchMachines as $m) {
            if (preg_match('/(\d+)/', $m->name, $matches)) {
                $num = (int)$matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }
        $next = max($maxNum, $branchMachines->count()) + 1;
        $numberStr = str_pad($next, 2, '0', STR_PAD_LEFT);
        $name = "{$typeLabel} #{$numberStr}";

        $machine = Machine::create([
            'branch_id' => $branchId,
            'name' => $name,
            'brand' => $brand ?: null,
            'model' => $model ?: null,
            'type' => $typeRaw,
            'status' => 'available',
        ]);

        $branchName = $machine->branch?->name ?? 'Branch';
        return back()->with('success', "Machine '{$machine->name}' added successfully to {$branchName}.");
    }

    public function updateMachine(Request $request, Machine $machine)
    {
        $admin = Auth::guard('admin')->user();
        $isOwner = $admin && $admin->isSuperAdmin();

        $rules = [
            'name' => 'required|string|max:100',
            'type' => 'required|in:washer,dryer',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'status' => 'required|in:available,in_use,maintenance',
        ];

        if ($isOwner) {
            $rules['branch_id'] = 'nullable|exists:branches,id';
        }

        $data = $request->validate($rules);

        if ($machine->status === 'in_use' && $data['status'] !== 'in_use') {
            if ($machine->active_service_request) {
                return back()->with('error', "Cannot change status of '{$machine->name}' because it is currently running an active laundry order (#REQ-{$machine->active_service_request->id}).");
            }
        }

        $updateData = [
            'name' => trim($data['name']),
            'type' => $data['type'],
            'brand' => trim($data['brand'] ?? '') ?: null,
            'model' => trim($data['model'] ?? '') ?: null,
            'status' => $data['status'],
        ];

        if ($isOwner && !empty($data['branch_id'])) {
            $updateData['branch_id'] = (int) $data['branch_id'];
        }

        $machine->update($updateData);

        return back()->with('success', "Machine '{$machine->name}' updated successfully.");
    }

    public function destroyMachine(Machine $machine)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->isSuperAdmin()) {
            abort(403, 'Only owners can delete machines.');
        }

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
        if ($machine->status === 'in_use' || $machine->active_service_request) {
            return back()->with('error', "Cannot toggle '{$machine->name}' because it is currently in use with an active laundry order.");
        }

        $newStatus = ($machine->status === 'available') ? 'maintenance' : 'available';
        $machine->update(['status' => $newStatus]);
        $statusLabel = $newStatus === 'available' ? 'Available' : 'Maintenance';

        return back()->with('success', "Machine '{$machine->name}' marked as {$statusLabel}.");
    }
    public function sales()
    {
        $period = request('period', 'all');
        $sort = request('sort', 'newest');
        $branchFilter = request('branch');
        $admin = Auth::guard('admin')->user();

        $effectiveBranchId = $admin->branch_id ?: ($branchFilter ?: null);

        $query = Sale::with(['serviceRequest.customer', 'serviceRequest.clothType', 'branch'])
            ->when($effectiveBranchId, fn ($q) => $q->where('sales.branch_id', $effectiveBranchId))
            ->leftJoin('service_requests', 'sales.service_request_id', '=', 'service_requests.id')
            ->leftJoin('customers', 'service_requests.customer_id', '=', 'customers.id')
            ->leftJoin('branches', 'sales.branch_id', '=', 'branches.id')
            ->leftJoin('cloth_types', 'service_requests.cloth_type_id', '=', 'cloth_types.id')
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
            'branch_asc' => $query->orderBy(DB::raw("LOWER(COALESCE(branches.name, 'Central Branch'))"), 'asc')->orderBy('sales.id', 'desc'),
            'branch_desc' => $query->orderBy(DB::raw("LOWER(COALESCE(branches.name, 'Central Branch'))"), 'desc')->orderBy('sales.id', 'desc'),
            'service_type_asc' => $query->orderBy(DB::raw("LOWER(COALESCE(cloth_types.name, 'Standard Wash'))"), 'asc')->orderBy('sales.id', 'desc'),
            'service_type_desc' => $query->orderBy(DB::raw("LOWER(COALESCE(cloth_types.name, 'Standard Wash'))"), 'desc')->orderBy('sales.id', 'desc'),
            'weight_asc' => $query->orderBy(DB::raw('COALESCE(service_requests.actual_weight, service_requests.estimated_weight, 0)'), 'asc')->orderBy('sales.id', 'desc'),
            'weight_desc' => $query->orderBy(DB::raw('COALESCE(service_requests.actual_weight, service_requests.estimated_weight, 0)'), 'desc')->orderBy('sales.id', 'desc'),
            'items_asc' => $query->orderBy(DB::raw('COALESCE(service_requests.actual_quantity, service_requests.quantity, 0)'), 'asc')->orderBy('sales.id', 'desc'),
            'items_desc' => $query->orderBy(DB::raw('COALESCE(service_requests.actual_quantity, service_requests.quantity, 0)'), 'desc')->orderBy('sales.id', 'desc'),
            default => $query->orderBy('sales.sold_at', 'desc'),
        };

        $sales = $query->get();

        $totalSales = $sales->sum('amount');
        $transactionCount = $sales->count();
        $averageSale = $transactionCount ? $sales->avg('amount') : 0;
        $branches = Branch::all();
        $isOwner = $admin && $admin->isSuperAdmin();

        // 1. Trend Map Aggregation (Time Series)
        $trendMap = [];
        if ($period === 'today') {
            for ($h = 6; $h <= 22; $h += 2) {
                $label = \Carbon\Carbon::createFromTime($h, 0)->format('g A');
                $trendMap[$h] = ['label' => $label, 'revenue' => 0.0, 'orders' => 0];
            }
            foreach ($sales as $sale) {
                if ($sale->sold_at) {
                    $hour = (int)$sale->sold_at->format('G');
                    $slot = max(6, min(22, (int)(floor($hour / 2) * 2)));
                    if (isset($trendMap[$slot])) {
                        $trendMap[$slot]['revenue'] += (float)$sale->amount;
                        $trendMap[$slot]['orders'] += 1;
                    }
                }
            }
        } elseif ($period === 'week') {
            $startOfWeek = now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $day = $startOfWeek->copy()->addDays($i);
                $trendMap[$day->format('Y-m-d')] = [
                    'label' => $day->format('D (M j)'),
                    'revenue' => 0.0,
                    'orders' => 0,
                ];
            }
            foreach ($sales as $sale) {
                if ($sale->sold_at) {
                    $dateKey = $sale->sold_at->format('Y-m-d');
                    if (isset($trendMap[$dateKey])) {
                        $trendMap[$dateKey]['revenue'] += (float)$sale->amount;
                        $trendMap[$dateKey]['orders'] += 1;
                    }
                }
            }
        } elseif ($period === 'month') {
            $startOfMonth = now()->startOfMonth();
            $daysInMonth = now()->daysInMonth;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $day = $startOfMonth->copy()->day($d);
                $trendMap[$day->format('Y-m-d')] = [
                    'label' => $day->format('M j'),
                    'revenue' => 0.0,
                    'orders' => 0,
                ];
            }
            foreach ($sales as $sale) {
                if ($sale->sold_at) {
                    $dateKey = $sale->sold_at->format('Y-m-d');
                    if (isset($trendMap[$dateKey])) {
                        $trendMap[$dateKey]['revenue'] += (float)$sale->amount;
                        $trendMap[$dateKey]['orders'] += 1;
                    }
                }
            }
        } else {
            // Default / All-time: Last 6 months up to current
            for ($i = 5; $i >= 0; $i--) {
                $m = now()->copy()->subMonths($i);
                $trendMap[$m->format('Y-m')] = [
                    'label' => $m->format('M Y'),
                    'revenue' => 0.0,
                    'orders' => 0,
                ];
            }
            foreach ($sales as $sale) {
                if ($sale->sold_at) {
                    $mKey = $sale->sold_at->format('Y-m');
                    if (!isset($trendMap[$mKey])) {
                        $trendMap[$mKey] = [
                            'label' => $sale->sold_at->format('M Y'),
                            'revenue' => 0.0,
                            'orders' => 0,
                        ];
                    }
                    $trendMap[$mKey]['revenue'] += (float)$sale->amount;
                    $trendMap[$mKey]['orders'] += 1;
                }
            }
            ksort($trendMap);
        }

        $trendCategories = array_column(array_values($trendMap), 'label');
        $trendRevenueSeries = array_map(fn($v) => round($v, 2), array_column(array_values($trendMap), 'revenue'));
        $trendOrdersSeries = array_column(array_values($trendMap), 'orders');

        // 2. Service Category Distribution (Pie / Donut)
        $serviceMap = [];
        foreach ($sales as $sale) {
            $clothName = $sale->serviceRequest?->clothType?->name ?? 'Standard Wash';
            if (!isset($serviceMap[$clothName])) {
                $serviceMap[$clothName] = [
                    'name' => $clothName,
                    'amount' => 0.0,
                    'count' => 0,
                ];
            }
            $serviceMap[$clothName]['amount'] += (float)$sale->amount;
            $serviceMap[$clothName]['count'] += 1;
        }
        uasort($serviceMap, fn($a, $b) => $b['amount'] <=> $a['amount']);

        $serviceLabels = array_keys($serviceMap);
        $serviceAmounts = array_map(fn($v) => round($v['amount'], 2), array_values($serviceMap));
        $serviceCounts = array_map(fn($v) => $v['count'], array_values($serviceMap));

        // 3. Multi-Branch Comparison (Owner Only)
        $branchLabels = [];
        $branchRevenueSeries = [];
        $branchOrdersSeries = [];
        if ($isOwner) {
            foreach ($branches as $branch) {
                $bSales = $sales->where('branch_id', $branch->id);
                $branchLabels[] = $branch->name;
                $branchRevenueSeries[] = round((float)$bSales->sum('amount'), 2);
                $branchOrdersSeries[] = $bSales->count();
            }
        }

        // 4. Physical Laundry Volume
        $totalWeight = $sales->sum(function ($s) {
            return (float)($s->serviceRequest?->actual_weight ?? $s->serviceRequest?->estimated_weight ?? 0);
        });
        $totalItems = $sales->sum(function ($s) {
            return (int)($s->serviceRequest?->actual_quantity ?? $s->serviceRequest?->quantity ?? 0);
        });

        return view('admin.sales', [
            'sales' => $sales,
            'totalSales' => $totalSales,
            'transactionCount' => $transactionCount,
            'averageSale' => $averageSale,
            'totalWeight' => $totalWeight,
            'totalItems' => $totalItems,
            'period' => $period,
            'sort' => $sort,
            'branches' => $branches,
            'branchFilter' => $branchFilter,
            'isOwner' => $isOwner,
            'trendCategories' => $trendCategories,
            'trendRevenueSeries' => $trendRevenueSeries,
            'trendOrdersSeries' => $trendOrdersSeries,
            'serviceLabels' => $serviceLabels,
            'serviceAmounts' => $serviceAmounts,
            'serviceCounts' => $serviceCounts,
            'branchLabels' => $branchLabels,
            'branchRevenueSeries' => $branchRevenueSeries,
            'branchOrdersSeries' => $branchOrdersSeries,
        ]);
    }

    public function feedback(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $isOwner = $admin && $admin->isSuperAdmin();

        $query = Feedback::select('feedbacks.*')
            ->with(['customer', 'serviceRequest.clothType', 'branch', 'replies.admin', 'replies.customer', 'resolvedBy'])
            ->leftJoin('customers', 'feedbacks.customer_id', '=', 'customers.id')
            ->leftJoin('branches', 'feedbacks.branch_id', '=', 'branches.id');

        // Branch scoping: If not super admin, scope to admin's branch
        if (!$isOwner && $admin->branch_id) {
            $query->where('feedbacks.branch_id', $admin->branch_id);
        } elseif ($request->filled('branch_id') && $isOwner) {
            $query->where('feedbacks.branch_id', $request->branch_id);
        }

        // Filter by Nature: feedback, complaint
        if ($request->filled('nature') && in_array($request->nature, ['feedback', 'complaint'])) {
            $query->where('feedbacks.nature', $request->nature);
        }

        // Filter by Status: open, in_progress, resolved, closed
        if ($request->filled('status') && in_array($request->status, ['open', 'in_progress', 'resolved', 'closed'])) {
            if ($request->status === 'open') {
                $query->where(function($q) {
                    $q->where('feedbacks.status', 'open')->orWhereNull('feedbacks.status');
                });
            } else {
                $query->where('feedbacks.status', $request->status);
            }
        }

        // Filter by Type (feedback types or complaint types)
        $validTypes = ['service', 'app', 'general', 'damaged_item', 'missing_clothes', 'machine_issue', 'staff_delay', 'other'];
        if ($request->filled('type') && in_array($request->type, $validTypes)) {
            $query->where('feedbacks.type', $request->type);
        }

        // Filter by Rating: 1 to 5
        if ($request->filled('rating') && in_array((int)$request->rating, [1, 2, 3, 4, 5])) {
            $query->where('feedbacks.rating', (int)$request->rating);
        }

        // Search in subject, comment, or customer username
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('feedbacks.subject', 'like', "%{$search}%")
                  ->orWhere('feedbacks.comment', 'like', "%{$search}%")
                  ->orWhere('customers.username', 'like', "%{$search}%");
            });
        }

        // Calculate summary stats on scoped base
        $statsQuery = Feedback::query();
        if (!$isOwner && $admin->branch_id) {
            $statsQuery->where('branch_id', $admin->branch_id);
        }

        $totalCount = $statsQuery->count();
        $feedbackBase = (clone $statsQuery)->where(function($q) {
            $q->where('nature', 'feedback')->orWhereNull('nature');
        });
        $feedbackCount = (clone $feedbackBase)->count();
        $avgRating = $feedbackCount > 0 ? round((clone $feedbackBase)->whereNotNull('rating')->avg('rating'), 1) : 0;
        $fiveStarCount = (clone $feedbackBase)->where('rating', 5)->count();
        $fiveStarPct = $feedbackCount > 0 ? round(($fiveStarCount / $feedbackCount) * 100) : 0;

        $complaintBase = (clone $statsQuery)->where('nature', 'complaint');
        $complaintCount = (clone $complaintBase)->count();

        $openComplaintCount = (clone $complaintBase)->where(function($q) {
            $q->where('status', 'open')->orWhereNull('status');
        })->count();
        $inProgressComplaintCount = (clone $complaintBase)->where('status', 'in_progress')->count();
        $resolvedComplaintCount = (clone $complaintBase)->whereIn('status', ['resolved', 'closed'])->count();

        $ratingCounts = [
            5 => $fiveStarCount,
            4 => (clone $feedbackBase)->where('rating', 4)->count(),
            3 => (clone $feedbackBase)->where('rating', 3)->count(),
            2 => (clone $feedbackBase)->where('rating', 2)->count(),
            1 => (clone $feedbackBase)->where('rating', 1)->count(),
        ];

        // Sorting
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest' => $query->orderBy('feedbacks.created_at', 'asc'),
            'name_asc' => $query->orderBy(DB::raw("LOWER(COALESCE(customers.username, ''))"), 'asc')->orderBy('feedbacks.id', 'desc'),
            'name_desc' => $query->orderBy(DB::raw("LOWER(COALESCE(customers.username, ''))"), 'desc')->orderBy('feedbacks.id', 'desc'),
            'type_asc' => $query->orderBy('feedbacks.type', 'asc')->orderBy('feedbacks.id', 'desc'),
            'type_desc' => $query->orderBy('feedbacks.type', 'desc')->orderBy('feedbacks.id', 'desc'),
            'rating_asc', 'rating_low' => $query->orderBy('feedbacks.rating', 'asc')->orderBy('feedbacks.id', 'desc'),
            'rating_desc', 'rating_high' => $query->orderBy('feedbacks.rating', 'desc')->orderBy('feedbacks.id', 'desc'),
            'branch_asc' => $query->orderBy(DB::raw("LOWER(COALESCE(branches.name, 'Central Branch'))"), 'asc')->orderBy('feedbacks.id', 'desc'),
            'branch_desc' => $query->orderBy(DB::raw("LOWER(COALESCE(branches.name, 'Central Branch'))"), 'desc')->orderBy('feedbacks.id', 'desc'),
            default => $query->orderBy('feedbacks.created_at', 'desc'),
        };

        $feedbacks = $query->paginate(12)->withQueryString();
        $branches = Branch::all();

        return view('admin.feedback', compact(
            'feedbacks',
            'branches',
            'totalCount',
            'feedbackCount',
            'complaintCount',
            'openComplaintCount',
            'inProgressComplaintCount',
            'resolvedComplaintCount',
            'avgRating',
            'ratingCounts',
            'fiveStarPct',
            'isOwner',
            'sort'
        ));
    }

    public function replyFeedback(Request $request, Feedback $feedback)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin->isSuperAdmin() && $admin->branch_id && $feedback->branch_id && $feedback->branch_id != $admin->branch_id) {
            abort(403, 'Unauthorized for this branch.');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $reply = FeedbackReply::create([
            'feedback_id' => $feedback->id,
            'sender_type' => 'admin',
            'sender_id' => $admin->id,
            'message' => trim($validated['message']),
            'is_read' => false,
        ]);

        // Auto move to in_progress if still open
        if ($feedback->isOpen()) {
            $feedback->update(['status' => 'in_progress']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully.',
                'reply' => [
                    'id' => $reply->id,
                    'sender_name' => $reply->sender_display_name,
                    'message' => $reply->message,
                    'time' => $reply->created_at->format('M d, Y · g:i A'),
                    'is_me' => true,
                ],
                'new_status' => $feedback->fresh()->status,
                'new_status_label' => $feedback->fresh()->status_label,
            ]);
        }

        return redirect()->back()->with('success', 'Reply posted and sent to customer.');
    }

    public function updateFeedbackStatus(Request $request, Feedback $feedback)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin->isSuperAdmin() && $admin->branch_id && $feedback->branch_id && $feedback->branch_id != $admin->branch_id) {
            abort(403, 'Unauthorized for this branch.');
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'resolution_note' => 'nullable|string|max:1000',
        ]);

        $updates = [
            'status' => $validated['status'],
        ];

        if (in_array($validated['status'], ['resolved', 'closed'])) {
            $updates['resolved_at'] = now();
            $updates['resolved_by'] = $admin->id;
            if ($request->filled('resolution_note')) {
                $updates['resolution_note'] = trim($validated['resolution_note']);
            }
        } elseif ($validated['status'] === 'in_progress' || $validated['status'] === 'open') {
            $updates['resolved_at'] = null;
            $updates['resolved_by'] = null;
        }

        $feedback->update($updates);

        // If resolution note was provided, post it in thread so customer can see the outcome
        if (!empty($validated['resolution_note']) && in_array($validated['status'], ['resolved', 'closed'])) {
            FeedbackReply::create([
                'feedback_id' => $feedback->id,
                'sender_type' => 'admin',
                'sender_id' => $admin->id,
                'message' => 'Resolution Note: ' . trim($validated['resolution_note']),
                'is_read' => false,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket status updated to ' . ucfirst(str_replace('_', ' ', $validated['status'])),
                'status' => $feedback->status,
                'status_label' => $feedback->status_label,
            ]);
        }

        return redirect()->back()->with('success', 'Complaint status updated successfully.');
    }

    public function getFeedbackDetails(Feedback $feedback)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin->isSuperAdmin() && $admin->branch_id && $feedback->branch_id && $feedback->branch_id != $admin->branch_id) {
            abort(403, 'Unauthorized for this branch.');
        }

        $feedback->load(['customer', 'branch', 'serviceRequest', 'resolvedBy', 'replies.admin', 'replies.customer']);

        // Mark any customer replies as read
        $feedback->replies()->where('sender_type', 'customer')->where('is_read', false)->update(['is_read' => true]);

        $complaintLabels = [
            'damaged_item' => 'Damaged Clothes',
            'missing_clothes' => 'Missing Items',
            'machine_issue' => 'Machine Problem',
            'staff_delay' => 'Staff Delay',
            'other' => 'Other Issue',
        ];

        return response()->json([
            'id' => $feedback->id,
            'nature' => $feedback->nature ?? 'feedback',
            'status' => $feedback->status ?: 'open',
            'status_label' => $feedback->status_label,
            'is_anonymous' => $feedback->isAnonymous(),
            'customer' => $feedback->isAnonymous() ? 'Anonymous' : ($feedback->customer->username ?? 'Customer #' . $feedback->customer_id),
            'type' => $feedback->isComplaint() ? ($complaintLabels[$feedback->type] ?? ucfirst(str_replace('_', ' ', $feedback->type))) : ucfirst($feedback->type),
            'rating' => $feedback->rating,
            'subject' => $feedback->subject ?? 'No Summary',
            'comment' => $feedback->comment,
            'aspects' => $feedback->aspects ?? [],
            'branch' => $feedback->branch->name ?? 'Central Branch',
            'order' => $feedback->service_request_id ? '#REQ-' . str_pad($feedback->service_request_id, 4, '0', STR_PAD_LEFT) : null,
            'order_url' => $feedback->service_request_id ? route('admin.service-requests.show', $feedback->service_request_id) : null,
            'date' => $feedback->created_at->format('M d, Y · g:i A'),
            'resolved_at' => $feedback->resolved_at ? $feedback->resolved_at->format('M d, Y · g:i A') : null,
            'resolved_by' => $feedback->resolvedBy?->username ? ucfirst($feedback->resolvedBy->username) : null,
            'resolution_note' => $feedback->resolution_note,
            'reply_url' => route('admin.feedback.reply', $feedback->id),
            'status_url' => route('admin.feedback.status', $feedback->id),
            'replies' => $feedback->replies->map(function ($r) {
                return [
                    'id' => $r->id,
                    'sender_type' => $r->sender_type,
                    'sender_name' => $r->sender_display_name,
                    'message' => $r->message,
                    'time' => $r->created_at->format('M d, Y · g:i A'),
                    'diff' => $r->created_at->diffForHumans(),
                    'is_me' => $r->sender_type === 'admin',
                ];
            }),
        ]);
    }

    public function destroyFeedback(Feedback $feedback)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->isSuperAdmin()) {
            abort(403, 'Unauthorized action. Only owners can delete feedback or complaints.');
        }

        $feedback->delete();
        return redirect()->route('admin.feedback')->with('success', 'Submission deleted successfully.');
    }

    public function checkNotifications(Request $request)
    {
        $adminUser = Auth::guard('admin')->user();
        if (!$adminUser) {
            return response()->json(['notifications' => []]);
        }

        $adminBranchId = $adminUser->branch_id;
        $notifications = collect();

        // Check for unread customer replies on complaints
        $unreadReplies = FeedbackReply::where('sender_type', 'customer')
            ->where('is_read', false)
            ->whereHas('feedback', function ($q) use ($adminUser, $adminBranchId) {
                if (!$adminUser->isSuperAdmin() && $adminBranchId) {
                    $q->where('branch_id', $adminBranchId);
                }
            })
            ->with(['feedback.customer'])
            ->latest()
            ->take(3)
            ->get();

        foreach ($unreadReplies as $rep) {
            $sender = $rep->feedback->isAnonymous() ? 'Anonymous Customer' : ($rep->feedback->customer->username ?? 'Customer');
            $notifications->push([
                'id' => 'reply_' . $rep->id,
                'type' => 'complaint_reply',
                'title' => 'Customer Replied: ' . ($rep->feedback->subject ?: 'Complaint Ticket'),
                'message' => $sender . ': "' . Str::limit($rep->message, 45) . '"',
                'status' => 'warning',
                'time' => $rep->created_at->toISOString(),
                'diff' => $rep->created_at->diffForHumans(),
                'timestamp' => $rep->created_at->timestamp * 1000,
                'url' => route('admin.feedback'),
            ]);
        }

        $recentReqs = \App\Models\ServiceRequest::when($adminBranchId, fn ($q) => $q->where('branch_id', $adminBranchId))
            ->with(['customer', 'clothType', 'statusHistories', 'branch'])
            ->latest('updated_at')
            ->take(10)
            ->get();
        foreach ($recentReqs as $req) {
            $customerName = $req->customer->username ?? 'Walk-in Customer';
            $reqNum = '#REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT);

            if ($req->status === 'pending') {
                $notifications->push([
                    'id' => 'new_' . $req->id,
                    'type' => 'new_request',
                    'title' => 'New Service Request ' . $reqNum,
                    'message' => $customerName . ' requested ' . ($req->clothType->name ?? 'Laundry') . '.',
                    'status' => 'pending',
                    'time' => $req->created_at->toISOString(),
                    'diff' => $req->created_at->diffForHumans(),
                    'timestamp' => $req->created_at->timestamp * 1000,
                    'url' => route('admin.service-requests.show', $req->id),
                ]);
            }

            if ($req->statusHistories->isNotEmpty()) {
                foreach ($req->statusHistories->sortByDesc('created_at')->take(1) as $hist) {
                    if ($hist->status !== 'pending') {
                        $notifications->push([
                            'id' => 'hist_' . $hist->id,
                            'type' => 'status_update',
                            'title' => 'Order ' . ucfirst(str_replace('_', ' ', $hist->status)),
                            'message' => $reqNum . ' (' . $customerName . ') is ' . ucfirst(str_replace('_', ' ', $hist->status)) . '.',
                            'status' => $hist->status,
                            'time' => $hist->created_at->toISOString(),
                            'diff' => $hist->created_at->diffForHumans(),
                            'timestamp' => $hist->created_at->timestamp * 1000,
                            'url' => route('admin.service-requests.show', $req->id),
                        ]);
                    }
                }
            } else {
                $notifications->push([
                    'id' => 'req_' . $req->id,
                    'type' => 'status_update',
                    'title' => 'Order ' . ucfirst(str_replace('_', ' ', $req->status)),
                    'message' => $reqNum . ' (' . $customerName . ') is ' . ucfirst(str_replace('_', ' ', $req->status)) . '.',
                    'status' => $req->status,
                    'time' => $req->updated_at->toISOString(),
                    'diff' => $req->updated_at->diffForHumans(),
                    'timestamp' => $req->updated_at->timestamp * 1000,
                    'url' => route('admin.service-requests.show', $req->id),
                ]);
            }
        }

        $sorted = $notifications->sortByDesc('timestamp')->take(7)->values();

        return response()->json([
            'notifications' => $sorted,
            'server_time' => now()->timestamp * 1000,
        ]);
    }
}
