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
use App\Models\Feedback;
use App\Models\FeedbackReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function showRegister()
    {
        return view('customer.register', [
            'securityQuestions' => Customer::SECURITY_QUESTIONS,
        ]);
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
            'security_question' => ['required', 'string', Rule::in(Customer::SECURITY_QUESTIONS)],
            'security_answer' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        $branchId = session('selected_branch_id');

        $customer = Customer::create([
            'username' => $request->username,
            'phone' => $request->phone ?? null,
            'security_question' => $request->security_question,
            'security_answer' => Hash::make(Customer::normalizeSecurityAnswer($request->security_answer)),
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

        $remember = $request->boolean('remember');
        if ($remember) {
            Auth::guard('customer')->setRememberDuration(60 * 24 * 7); // 7 days
        }

        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
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
                'user_agent' => substr($request->userAgent() ?? '', 0, 255),
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
            'user_agent' => substr($request->userAgent() ?? '', 0, 255),
            'login_at' => now(),
            'status' => 'failed',
        ]);

        return back(fallback: route('customer.login'))
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
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }

    public function showForgotPassword(Request $request)
    {
        $customer = null;
        $username = $request->query('username', old('username'));

        if ($username) {
            $found = Customer::where('username', $username)->first();
            if ($found && $found->security_question && $found->security_answer) {
                $customer = $found;
            }
        }

        return view('customer.forgot-password', [
            'customer' => $customer,
            'username' => $username,
        ]);
    }

    public function verifyForgotPasswordUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $customer = Customer::where('username', trim($request->username))->first();

        if (!$customer) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'We could not find an account with that username.']);
        }

        if (!$customer->security_question || !$customer->security_answer) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'This account does not have a security question set up yet. Please ask a branch manager or admin to reset your password.']);
        }

        return redirect()->route('customer.password.request', ['username' => $customer->username]);
    }

    public function resetForgotPassword(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'security_answer' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $customer = Customer::where('username', trim($request->username))->first();

        if (!$customer || !$customer->security_question || !$customer->security_answer) {
            return redirect()->route('customer.password.request')
                ->withErrors(['username' => 'Unable to verify security question for this account.']);
        }

        $normalizedAnswer = Customer::normalizeSecurityAnswer($request->security_answer);

        if (!Hash::check($normalizedAnswer, $customer->security_answer)) {
            return redirect()->route('customer.password.request', ['username' => $customer->username])
                ->withErrors(['security_answer' => 'Incorrect answer to the security question. Please try again.']);
        }

        $customer->password = Hash::make($request->password);
        $customer->save();

        return redirect()->route('customer.login')
            ->with('success', 'Password reset successfully! You can now log in with your new password.');
    }

    public function profile()
    {
        return view('customer.profile', [
            'branches' => Branch::where('is_active', true)->get(),
            'securityQuestions' => Customer::SECURITY_QUESTIONS,
        ]);
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

    public function updateSecurityQuestion(Request $request)
    {
        $request->validate([
            'current_password_for_security' => 'required',
            'security_question' => ['required', 'string', Rule::in(Customer::SECURITY_QUESTIONS)],
            'security_answer' => 'required|string|max:255',
        ]);

        $customer = Auth::guard('customer')->user();

        if (!Hash::check($request->current_password_for_security, $customer->password)) {
            return back()->withErrors(['current_password_for_security' => 'Current password is incorrect.']);
        }

        $customer->security_question = $request->security_question;
        $customer->security_answer = Hash::make(Customer::normalizeSecurityAnswer($request->security_answer));
        $customer->save();

        return back()->with('success', 'Security question and answer updated successfully!');
    }
    public function about()
    {
        $branch = Auth::guard('customer')->user()->branch ?? Branch::first();
        $clothTypes = ClothType::with(['pricingTiers' => function ($q) {
            $q->where('is_active', true)->orderBy('min_weight')->orderBy('price');
        }])->where('is_active', true)->get();
        $addons = Addon::where('is_active', true)->get();

        return view('customer.about', compact('branch', 'clothTypes', 'addons'));
    }

    public function dashboard()
    {
        $customerId = Auth::guard('customer')->id();
        $activeCycles = ServiceRequest::where('customer_id', $customerId)
            ->whereIn('status', ['washing', 'drying'])
            ->get();
        foreach ($activeCycles as $ac) {
            $ac->syncCycleProgress();
        }

        $serviceRequests = ServiceRequest::where('customer_id', $customerId)
            ->with(['branch', 'clothType', 'pricingTier', 'washingMachine', 'dryerMachine'])
            ->latest()
            ->get();

        return view('customer.dashboard', compact('serviceRequests'));
    }

    public function requests()
    {
        $customerId = Auth::guard('customer')->id();
        $activeCycles = ServiceRequest::where('customer_id', $customerId)
            ->whereIn('status', ['washing', 'drying'])
            ->get();
        foreach ($activeCycles as $ac) {
            $ac->syncCycleProgress();
        }

        $sort = request('sort', 'newest');
        $filter = request('filter', 'all');

        $query = ServiceRequest::with(['clothType', 'pricingTier', 'addons', 'branch', 'washingMachine', 'dryerMachine'])
            ->where('service_requests.customer_id', $customerId)
            ->leftJoin('cloth_types', 'service_requests.cloth_type_id', '=', 'cloth_types.id')
            ->select('service_requests.*');

        match ($sort) {
            'oldest' => $query->orderBy('service_requests.id', 'asc'),
            'weight_desc' => $query->orderBy('service_requests.estimated_weight', 'desc')->orderBy('service_requests.id', 'desc'),
            'weight_asc' => $query->orderBy('service_requests.estimated_weight', 'asc')->orderBy('service_requests.id', 'desc'),
            'id_asc' => $query->orderBy('service_requests.id', 'asc'),
            'id_desc' => $query->orderBy('service_requests.id', 'desc'),
            'items_asc', 'quantity_asc' => $query->orderBy('service_requests.quantity', 'asc')->orderBy('service_requests.id', 'desc'),
            'items_desc', 'quantity_desc' => $query->orderBy('service_requests.quantity', 'desc')->orderBy('service_requests.id', 'desc'),
            'cloth_type_asc' => $query->orderBy('cloth_types.name', 'asc')->orderBy('service_requests.id', 'desc'),
            'cloth_type_desc' => $query->orderBy('cloth_types.name', 'desc')->orderBy('service_requests.id', 'desc'),
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
        $serviceRequest->syncCycleProgress();
        $serviceRequest->load(['branch', 'clothType', 'addons', 'statusHistories', 'washingMachine', 'dryerMachine', 'feedbacks', 'items']);
        return view('customer.request-details', compact('serviceRequest'));
    }

    public function cycleSync(ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->customer_id === Auth::guard('customer')->id(), 403);
        $changed = $serviceRequest->syncCycleProgress();
        $serviceRequest->refresh();
        return response()->json([
            'success' => true,
            'changed' => $changed,
            'status' => $serviceRequest->status,
            'cycle_phase' => $serviceRequest->cycle_phase,
            'weight_verification_status' => $serviceRequest->weight_verification_status,
            'actual_weight' => $serviceRequest->actual_weight,
            'final_price' => $serviceRequest->final_price ?? $serviceRequest->estimated_price,
            'info' => $serviceRequest->cycle_info,
        ]);
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

        $customer = Auth::guard('customer')->user();
        abort_unless($customer->branch_id, 422, 'Please select a branch in your profile before submitting a service request.');

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
                        ];
                    }
                }
            }
        }

        $comforterSize = null;
        if ($clothType->requires_size || !empty($tier->size)) {
            $quantity = 1;
            $estimatedWeight = null;
            $comforterSize = $tier->size ?? $tier->name;
            $basePrice = $tier->price;
        } else {
            $quantity = ($totalItemCount > 0) ? $totalItemCount : (!empty($data['quantity']) ? (int)$data['quantity'] : 1);
            $rawWeight = isset($data['estimated_weight']) ? (float)$data['estimated_weight'] : 0;
            $estimatedWeight = ($rawWeight > 0) ? $rawWeight : ($tier->min_weight ?? 1);
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

        if (!empty($itemsToCreate)) {
            foreach ($itemsToCreate as $itemData) {
                $requestModel->items()->create($itemData);
            }
        }

        $requestModel->addons()->attach($addons->mapWithKeys(fn ($addon) => [$addon->id => ['price' => $addon->price]]));

        ServiceRequestStatusHistory::create([
            'service_request_id' => $requestModel->id,
            'status' => 'pending',
            'started_at' => now(),
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Service request submitted successfully.');
    }

    public function feedback(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        // Fetch completed or recent requests so customer can optionally link review to an order
        $recentRequests = ServiceRequest::where('customer_id', $customer->id)
            ->with(['branch', 'clothType'])
            ->latest()
            ->take(20)
            ->get();

        $selectedRequestId = $request->query('request_id');

        // Customer's past feedbacks with threaded replies
        $feedbacks = Feedback::where('customer_id', $customer->id)
            ->with(['serviceRequest.clothType', 'branch', 'replies.admin', 'resolvedBy'])
            ->latest()
            ->get();

        return view('customer.feedback', compact('recentRequests', 'selectedRequestId', 'feedbacks'));
    }

    public function storeFeedback(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $nature = $request->input('nature', 'feedback');

        if ($nature === 'complaint') {
            $validated = $request->validate([
                'nature' => 'required|in:complaint',
                'type' => 'required|string|in:damaged_item,missing_clothes,machine_issue,staff_delay,other',
                'is_anonymous' => 'nullable|boolean',
                'service_request_id' => 'nullable|exists:service_requests,id',
                'subject' => 'nullable|string|max:60',
                'comment' => 'required|string|max:500',
            ], [
                'comment.required' => 'Please provide details about what happened with your complaint.',
            ]);
            $rating = null;
            $aspects = [];
            $status = 'open';
            $successMsg = 'Your complaint has been submitted to management. We take all concerns seriously and will look into this promptly.';
        } else {
            $validated = $request->validate([
                'nature' => 'nullable|in:feedback',
                'type' => 'required|in:service,app,general',
                'is_anonymous' => 'nullable|boolean',
                'rating' => 'required|integer|min:1|max:5',
                'service_request_id' => 'nullable|exists:service_requests,id',
                'subject' => 'nullable|string|max:60',
                'comment' => 'nullable|string|max:300',
                'aspects' => 'nullable|array',
                'aspects.*' => 'string|max:50',
            ]);
            $nature = 'feedback';
            $rating = $validated['rating'];
            $aspects = $validated['aspects'] ?? [];
            $status = 'resolved'; // general review is considered resolved upon submission
            $successMsg = 'Thank you! Your feedback has been submitted successfully.';
        }

        $branchId = $customer->branch_id;
        if (!empty($validated['service_request_id'])) {
            $serviceReq = ServiceRequest::where('id', $validated['service_request_id'])
                ->where('customer_id', $customer->id)
                ->first();
            if ($serviceReq) {
                $branchId = $serviceReq->branch_id ?? $branchId;
            } else {
                $validated['service_request_id'] = null;
            }
        }

        Feedback::create([
            'customer_id' => $customer->id,
            'service_request_id' => $validated['service_request_id'] ?? null,
            'branch_id' => $branchId,
            'nature' => $nature,
            'status' => $status,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'type' => $validated['type'],
            'rating' => $rating,
            'subject' => $validated['subject'] ?? null,
            'comment' => $validated['comment'] ?? null,
            'aspects' => $aspects,
        ]);

        return redirect()->route('customer.feedback')->with('success', $successMsg);
    }

    public function replyFeedback(Request $request, Feedback $feedback)
    {
        $customer = Auth::guard('customer')->user();
        if ($feedback->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access to this feedback thread.');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $reply = FeedbackReply::create([
            'feedback_id' => $feedback->id,
            'sender_type' => 'customer',
            'sender_id' => $customer->id,
            'message' => trim($validated['message']),
            'is_read' => false,
        ]);

        // If previously resolved or closed, reopen ticket to in_progress so staff knows follow-up is needed
        if (in_array($feedback->status, ['resolved', 'closed'])) {
            $feedback->update([
                'status' => 'in_progress',
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your reply has been sent.',
                'reply' => [
                    'id' => $reply->id,
                    'sender_type' => 'customer',
                    'sender_name' => 'You',
                    'message' => $reply->message,
                    'time_diff' => 'just now',
                    'created_at' => $reply->created_at->format('M d, Y · h:i A'),
                ],
                'status' => $feedback->fresh()->status,
                'status_label' => $feedback->fresh()->status_label,
            ]);
        }

        return redirect()->route('customer.feedback')->with('success', 'Your reply has been sent to the management team.');
    }

    public function getFeedbackReplies(Feedback $feedback)
    {
        $customer = Auth::guard('customer')->user();
        if ($feedback->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access to this feedback thread.');
        }

        // Mark staff replies as read
        $feedback->replies()->where('sender_type', 'admin')->where('is_read', false)->update(['is_read' => true]);

        $replies = $feedback->replies()->with('admin')->orderBy('created_at', 'asc')->get()->map(function($r) {
            return [
                'id' => $r->id,
                'sender_type' => $r->sender_type,
                'sender_name' => $r->sender_type === 'admin' ? $r->sender_display_name : 'You',
                'message' => $r->message,
                'time_diff' => $r->created_at->diffForHumans(),
                'created_at' => $r->created_at->format('M d, Y · h:i A'),
            ];
        });

        return response()->json([
            'status' => $feedback->status,
            'status_label' => $feedback->status_label,
            'resolution_note' => $feedback->resolution_note,
            'resolved_at' => $feedback->resolved_at ? $feedback->resolved_at->format('M d, Y · h:i A') : null,
            'replies' => $replies,
        ]);
    }

    public function resolveFeedback(Request $request, Feedback $feedback)
    {
        $customer = Auth::guard('customer')->user();
        if ($feedback->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access to this feedback.');
        }

        $feedback->update([
            'status' => 'closed',
            'resolved_at' => now(),
        ]);

        return redirect()->route('customer.feedback')->with('success', 'Thank you! Your complaint ticket has been marked as resolved and closed.');
    }

    public function checkNotifications(Request $request)
    {
        $customerUser = Auth::guard('customer')->user();
        if (!$customerUser) {
            return response()->json(['notifications' => []]);
        }

        $notifications = collect();

        // Check for unread staff replies on customer's feedback/complaints
        $unreadReplies = FeedbackReply::where('sender_type', 'admin')
            ->where('is_read', false)
            ->whereHas('feedback', function ($q) use ($customerUser) {
                $q->where('customer_id', $customerUser->id);
            })
            ->with('feedback')
            ->latest()
            ->take(3)
            ->get();

        foreach ($unreadReplies as $rep) {
            $notifications->push([
                'id' => 'reply_' . $rep->id,
                'type' => 'complaint_reply',
                'title' => 'Management Replied to Your ' . ($rep->feedback->isComplaint() ? 'Complaint' : 'Feedback'),
                'message' => 'Staff: "' . Str::limit($rep->message, 50) . '"',
                'status' => 'info',
                'time' => $rep->created_at->toISOString(),
                'diff' => $rep->created_at->diffForHumans(),
                'timestamp' => $rep->created_at->timestamp * 1000,
                'url' => route('customer.feedback'),
            ]);
        }

        $recentReqs = \App\Models\ServiceRequest::where('customer_id', $customerUser->id)
            ->with(['branch', 'clothType', 'statusHistories'])
            ->latest('updated_at')
            ->take(8)
            ->get();

        foreach ($recentReqs as $req) {
            if (!empty($req->weight_verification_status) && $req->weight_verification_status !== 'pending') {
                $verHist = $req->statusHistories->firstWhere('status', 'received');
                $verTime = $verHist ? $verHist->created_at : $req->created_at;

                $notifications->push([
                    'id' => 'ver_' . $req->id,
                    'type' => 'verification',
                    'title' => 'Weight Verification Update',
                    'message' => 'Request #REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT) . ' weight has been ' . $req->weight_verification_status . ($req->actual_weight ? ' (' . $req->actual_weight . ' kg)' : '') . '.',
                    'status' => 'verified',
                    'time' => $verTime->toISOString(),
                    'diff' => $verTime->diffForHumans(),
                    'timestamp' => $verTime->timestamp * 1000,
                    'url' => route('customer.requests.show', $req->id),
                ]);
            }

            if ($req->statusHistories->isNotEmpty()) {
                foreach ($req->statusHistories->sortByDesc('created_at')->take(2) as $hist) {
                    $notifications->push([
                        'id' => 'hist_' . $hist->id,
                        'type' => 'status_update',
                        'title' => 'Order Status: ' . ucfirst(str_replace('_', ' ', $hist->status)),
                        'message' => 'Request #REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT) . ' is now ' . ucfirst(str_replace('_', ' ', $hist->status)) . '.',
                        'status' => $hist->status,
                        'time' => $hist->created_at->toISOString(),
                        'diff' => $hist->created_at->diffForHumans(),
                        'timestamp' => $hist->created_at->timestamp * 1000,
                        'url' => route('customer.requests.show', $req->id),
                    ]);
                }
            } else {
                $notifications->push([
                    'id' => 'req_' . $req->id,
                    'type' => 'status_update',
                    'title' => 'Order ' . ucfirst(str_replace('_', ' ', $req->status)),
                    'message' => 'Request #REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT) . ' (' . ($req->clothType->name ?? 'Laundry') . ') status is ' . ucfirst(str_replace('_', ' ', $req->status)) . '.',
                    'status' => $req->status,
                    'time' => $req->updated_at->toISOString(),
                    'diff' => $req->updated_at->diffForHumans(),
                    'timestamp' => $req->updated_at->timestamp * 1000,
                    'url' => route('customer.requests.show', $req->id),
                ]);
            }
        }

        $sorted = $notifications->sortByDesc('timestamp')->take(6)->values();

        $activeRequests = \App\Models\ServiceRequest::where('customer_id', $customerUser->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->get(['id', 'status', 'cycle_phase', 'weight_verification_status', 'actual_weight', 'final_price', 'updated_at'])
            ->map(function ($req) {
                return [
                    'id' => (string) $req->id,
                    'status' => $req->status,
                    'cycle_phase' => $req->cycle_phase,
                    'weight_verification_status' => $req->weight_verification_status,
                    'actual_weight' => $req->actual_weight,
                    'final_price' => $req->final_price,
                    'updated_at' => $req->updated_at ? $req->updated_at->timestamp * 1000 : null,
                ];
            });

        return response()->json([
            'notifications' => $sorted,
            'active_requests' => $activeRequests,
            'server_time' => now()->timestamp * 1000,
        ]);
    }
}
