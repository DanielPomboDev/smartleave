<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Notifications\LeaveRequestStatusUpdated;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /**
     * Process a leave request approval or rejection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processApproval(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'decision' => 'required|in:approve,reject',
            'comments' => 'nullable|string|max:500',
        ]);

        // Find the leave request
        $leaveRequest = LeaveRequest::findOrFail($id);

        // Update the leave request status
        $leaveRequest->status = $validated['decision'] === 'approve'
            ? LeaveRequest::STATUS_APPROVED
            : LeaveRequest::STATUS_DISAPPROVED;

        // Save comments if provided
        if (!empty($validated['comments'])) {
            $leaveRequest->comments = $validated['comments'];
        }

        $leaveRequest->save();

        // Redirect with success message
        $action = $validated['decision'] === 'approve' ? 'approved' : 'rejected';
        return redirect()->route('hr.leave.requests')
            ->with('success', "Leave request has been successfully {$action}.");
    }

    /**
     * Cancel a leave request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelRequest($id)
    {
        // Find the leave request
        $leaveRequest = LeaveRequest::findOrFail($id);

        // Check if the authenticated user owns this request
        if ($leaveRequest->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to cancel this leave request.');
        }

        // Check if the leave request can be cancelled
        if (!$leaveRequest->isCancellable()) {
            return redirect()->back()->with('error', 'This leave request cannot be cancelled at this stage.');
        }

        // Cancel the leave request
        if ($leaveRequest->cancel()) {
            return redirect()->back()->with('success', 'Leave request has been successfully cancelled.');
        }

        return redirect()->back()->with('error', 'Failed to cancel the leave request. Please try again.');
    }

    /**
     * Store a newly created leave request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'leaveType' => 'required|string|in:vacation,sick',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'numberOfDays' => 'required|integer|min:1',
            'locationType' => 'required|string',
            'location_specify' => 'nullable|string|max:255',
            'commutation' => 'nullable|boolean',
        ]);

        // Additional validation for vacation leave timing
        if ($validated['leaveType'] === 'vacation') {
            $startDate = Carbon::parse($validated['startDate']);
            $today = Carbon::today();
            $daysDifference = $today->diffInDays($startDate, false); // false to get signed difference

            if ($daysDifference < 5) {
                return redirect()->back()
                    ->withErrors(['startDate' => 'Vacation leave must be applied at least 5 days before the start date.'])
                    ->withInput();
            }
        }

        // Additional validation based on leave type
        if ($validated['leaveType'] === 'vacation') {
            $request->validate([
                'vacationSubtype' => 'required|string',
                'vacationOtherSpecify' => 'required_if:vacationSubtype,other|nullable|string|max:255',
            ]);

            // Get the full text for vacation subtype
            if ($request->input('vacationSubtype') === 'employment') {
                $subtype = 'To seek employment';
            } elseif ($request->input('vacationSubtype') === 'other') {
                $subtype = $request->input('vacationOtherSpecify');
            } else {
                $subtype = $request->input('vacationSubtype');
            }
        } else { // sick leave
            $request->validate([
                'sickSubtype' => 'required|string',
                'sickOtherSpecify' => 'required_if:sickSubtype,other|nullable|string|max:255',
            ]);

            // Get the full text for sick subtype
            if ($request->input('sickSubtype') === 'other') {
                $subtype = $request->input('sickOtherSpecify');
            } else {
                // Map the value to its full text
                $sickSubtypeMap = [
                    'hospital' => 'In Hospital',
                    'outpatient' => 'Outpatient',
                    // Add other mappings as needed
                ];
                $subtype = $sickSubtypeMap[$request->input('sickSubtype')] ?? $request->input('sickSubtype');
            }
        }

        // Calculate number of days (in case it's different from what was submitted)
        $startDate = Carbon::parse($validated['startDate']);
        $endDate = Carbon::parse($validated['endDate']);
        $numberOfDays = $startDate->diffInDays($endDate) + 1; // Include both start and end dates

        // No signature processing needed

        // Create the leave request
        $leaveRequest = new LeaveRequest();
        $leaveRequest->user_id = Auth::id();
        $leaveRequest->leave_type = $validated['leaveType'];
        $leaveRequest->subtype = $subtype;
        $leaveRequest->start_date = $validated['startDate'];
        $leaveRequest->end_date = $validated['endDate'];
        $leaveRequest->number_of_days = $numberOfDays;
        // Handle location data with full text
        if ($validated['locationType'] === 'abroad' && !empty($validated['location_specify'])) {
            // For abroad, store the specified country
            $whereSpent = $validated['location_specify'];
        } else if ($validated['locationType'] === 'outpatient' && !empty($validated['location_specify'])) {
            // For outpatient, store the specified location
            $whereSpent = $validated['location_specify'];
        } else {
            // For other location types, use the mapped full text
            $locationTypeMap = [
                'philippines' => 'Within the Philippines',
                'abroad' => 'Abroad', // Only used if no specification provided
                'hospital' => 'In Hospital',
                'outpatient' => 'Outpatient' // Only used if no specification provided
            ];

            $whereSpent = $locationTypeMap[$validated['locationType']] ?? $validated['locationType'];
        }
        $leaveRequest->where_spent = $whereSpent;
        // Handle commutation as boolean (1 for requested, 0 for not requested)
        $leaveRequest->commutation = isset($validated['commutation']) && $validated['commutation'] == '1';
        $leaveRequest->status = LeaveRequest::STATUS_PENDING;
        $leaveRequest->save();
        
        // Notify department admins about the new leave request
        $departmentAdmins = User::where('department_id', Auth::user()->department_id)
            ->where('user_type', 'department_admin')
            ->get();
            
        foreach ($departmentAdmins as $admin) {
            // Only send notification if admin has enabled in-app notifications for leave requests
            if ($admin->notificationPreferences && $admin->notificationPreferences->in_app_leave_requests) {
                $admin->notify(new LeaveRequestStatusUpdated($leaveRequest, 'new_request'));
            }
        }

        // Redirect with success message - stay on the same page
        return redirect()->back()->with('success', 'Your leave request has been submitted successfully and is pending approval.');
    }

    /**
     * Display the leave request history for the authenticated user with filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function history(Request $request)
    {
        // Get filter parameters
        $month = $request->input('month', 'all');
        $leaveType = $request->input('leave_type', 'all');
        $status = $request->input('status', 'all');

        // Start building the query
        $query = LeaveRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($month !== 'all') {
            $query->whereMonth('start_date', $month);
        }

        if ($leaveType !== 'all') {
            $query->where('leave_type', $leaveType);
        }

        if ($status !== 'all') {
            $statusMap = [
                'approved' => LeaveRequest::STATUS_APPROVED,
                'pending' => LeaveRequest::STATUS_PENDING,
                'disapproved' => LeaveRequest::STATUS_DISAPPROVED,
                'recommended' => LeaveRequest::STATUS_RECOMMENDED,
                'hr_approved' => LeaveRequest::STATUS_HR_APPROVED,
                'cancelled' => LeaveRequest::STATUS_CANCELLED
            ];
            $query->where('status', $statusMap[$status]);
        }

        // Get paginated results
        $leaveRequests = $query->paginate(10);

        // Pass filter values to view for maintaining state
        return view('employee_leave_history', [
            'leaveRequests' => $leaveRequests,
            'filters' => [
                'month' => $month,
                'leave_type' => $leaveType,
                'status' => $status
            ]
        ]);
    }

    /**
     * Display a list of users.
     *
     * @return \Illuminate\View\View
     */
    public function showUsers()
    {
        // Retrieve all users
        $users = \App\Models\User::all();

        // Pass users data to the view
        return view('hr_employees', ['users' => $users]);
    }

    /**
     * Get recent leave requests for the department dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function departmentDashboard()
    {
        // Get the authenticated user's department
        $departmentId = Auth::user()->department_id;

        // Get recent leave requests from users in the same department (including cancelled requests)
        $leaveRequests = LeaveRequest::with('user')
            ->whereHas('user', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get department statistics
        $stats = [
            'pending' => LeaveRequest::whereHas('user', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })->where('status', LeaveRequest::STATUS_PENDING)->count(),
            'approved_this_month' => LeaveRequest::whereHas('user', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })->where('status', LeaveRequest::STATUS_APPROVED)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'rejected_this_month' => LeaveRequest::whereHas('user', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })->where('status', LeaveRequest::STATUS_DISAPPROVED)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'department_employees' => User::where('department_id', $departmentId)->count()
        ];

        return view('department_dashboard', [
            'leaveRequests' => $leaveRequests,
            'stats' => $stats
        ]);
    }

    /**
     * Show the department leave approval form.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showDepartmentApproval($id)
    {
        // Get the leave request with user and department information
        $leaveRequest = LeaveRequest::with(['user', 'user.department'])
            ->findOrFail($id);

        // Check if the leave request belongs to a user in the same department
        if ($leaveRequest->user->department_id !== Auth::user()->department_id) {
            abort(403, 'You are not authorized to view this leave request.');
        }

        return view('department_leave_approve', [
            'leaveId' => $id,
            'leaveRequest' => $leaveRequest
        ]);
    }

    /**
     * Process the department leave approval.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processDepartmentApproval(Request $request, $id)
    {
        try {
            $leaveRequest = LeaveRequest::with('user')->findOrFail($id);

            // Check if the leave request belongs to a user in the same department
            if ($leaveRequest->user->department_id !== Auth::user()->department_id) {
                return redirect()->back()->with('error', 'You are not authorized to approve this leave request.');
            }

            // Check if the leave request is still pending
            if ($leaveRequest->status !== LeaveRequest::STATUS_PENDING) {
                return redirect()->back()->with('error', 'This leave request has already been processed.');
            }

            // Validate the request
            $validated = $request->validate([
                'recommendation' => 'required|in:approve,disapprove',
                'approval_reason' => 'nullable|string|max:500',
                'disapproval_reason' => 'required_if:recommendation,disapprove|nullable|string|max:500',
            ], [
                'disapproval_reason.required_if' => 'Please provide a reason for disapproval.',
                'disapproval_reason.string' => 'The disapproval reason must be text.',
                'approval_reason.string' => 'The approval reason must be text.',
            ]);

            // Create leave recommendation (new schema)
            $recommendation = new \App\Models\LeaveRecommendation();
            $recommendation->leave_id = $id;
            $recommendation->recommendation = $validated['recommendation'];
            $recommendation->remarks = $validated['recommendation'] === 'approve'
                ? ($validated['approval_reason'] ?? '')
                : ($validated['disapproval_reason'] ?? '');
            $recommendation->department_admin_id = Auth::id();
            $recommendation->save();

            // Update the leave request status
            if ($validated['recommendation'] === 'disapprove') {
                DB::statement("UPDATE leave_requests SET status = ? WHERE id = ?", ['disapproved', $id]);
            } else {
                DB::statement("UPDATE leave_requests SET status = ? WHERE id = ?", ['recommended', $id]);
            }

            // Send notification to HR if user has enabled notifications
            $hrUsers = User::where('user_type', 'hr')->get();

            foreach ($hrUsers as $hrUser) {
                // Only send notification if HR user has enabled in-app notifications
                if ($hrUser->notificationPreferences && $hrUser->notificationPreferences->in_app_leave_requests) {
                    $hrUser->notify(new LeaveRequestStatusUpdated($leaveRequest, 'recommended'));
                }
            }

            return redirect()->route('department.dashboard')
                ->with('success', 'Leave request has been ' . ($validated['recommendation'] === 'approve' ? 'approved' : 'disapproved') . ' by department.');
        } catch (\Exception $e) {
            Log::error('Department approval error', [
                'leave_request_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => Auth::id(),
                'user_type' => Auth::user()->user_type ?? 'not logged in'
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while processing the leave request: ' . $e->getMessage());
        }
    }

    /**
     * Show the HR leave approval form.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showHRApproval($id)
    {
        // Get the leave request with user and department information
        $leaveRequest = LeaveRequest::with(['user', 'user.department', 'recommendations'])
            ->findOrFail($id);

        return view('hr_leave_approve', [
            'leaveId' => $id,
            'leaveRequest' => $leaveRequest
        ]);
    }

    /**
     * Process the HR leave approval.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processHRApproval(Request $request, $id)
    {
        try {
            $leaveRequest = LeaveRequest::with(['user', 'recommendations'])->findOrFail($id);

            // Must be recommended and have an approved recommendation
            if ($leaveRequest->status !== LeaveRequest::STATUS_RECOMMENDED) {
                return redirect()->back()->with('error', 'This leave request is not yet recommended by the department.');
            }
            if (!$leaveRequest->recommendations()->where('recommendation', 'approve')->exists()) {
                return redirect()->back()->with('error', 'No department recommendation found.');
            }

            // Validate the request
            $validated = $request->validate([
                'approval' => 'required|in:approve,disapprove',
                'approved_for' => 'nullable|string|max:255',
                'dissapproved_due_to' => 'nullable|string|max:500',
            ]);

            // Record HR approval in leave_approvals
            \App\Models\LeaveApproval::create([
                'hr_manager_id' => Auth::id(),
                'leave_id' => $id,
                'approval' => $validated['approval'],
                'approved_for' => $validated['approved_for'] ?? null,
                'dissapproved_due_to' => $validated['dissapproved_due_to'] ?? null,
            ]);

            // Update the leave request status only (comments and timestamps moved to leave_approvals table)
            $leaveRequest->status = $validated['approval'] === 'approve'
                ? LeaveRequest::STATUS_HR_APPROVED
                : LeaveRequest::STATUS_DISAPPROVED;
            $leaveRequest->save();

            // Send notification to the employee if they have enabled notifications
            if ($leaveRequest->user->notificationPreferences && 
                (($validated['approval'] === 'approve' && $leaveRequest->user->notificationPreferences->in_app_approvals) ||
                 ($validated['approval'] === 'disapprove' && $leaveRequest->user->notificationPreferences->in_app_rejections))) {
                $leaveRequest->user->notify(new LeaveRequestStatusUpdated($leaveRequest));
            }
            
            // Notify mayor if the request was approved by HR
            if ($validated['approval'] === 'approve') {
                $mayors = User::where('user_type', 'mayor')->get();
                
                foreach ($mayors as $mayor) {
                    // Only send notification if mayor has enabled in-app notifications for leave requests
                    if ($mayor->notificationPreferences && $mayor->notificationPreferences->in_app_leave_requests) {
                        $mayor->notify(new LeaveRequestStatusUpdated($leaveRequest, 'hr_approved'));
                    }
                }
            }

            return redirect()->route('hr.dashboard')
                ->with('success', 'Leave request has been ' . ($validated['approval'] === 'approve' ? 'approved' : 'disapproved') . ' by HR.');
        } catch (\Exception $e) {
            Log::error('HR approval error', [
                'leave_request_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => Auth::id(),
                'user_type' => Auth::user()->user_type ?? 'not logged in'
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while processing the leave request: ' . $e->getMessage());
        }
    }

    /**
     * Display department leave requests with filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function departmentLeaveRequests(Request $request)
    {
        // Get filter parameters
        $status = $request->input('status', 'all');
        $leaveType = $request->input('leave_type', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search', '');

        // Get the authenticated user's department
        $departmentId = Auth::user()->department_id;

        // Start building the query
        $query = LeaveRequest::with(['user', 'user.department'])
            ->whereHas('user', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($status !== 'all') {
            $statusMap = [
                'pending' => LeaveRequest::STATUS_PENDING,
                'recommended' => LeaveRequest::STATUS_RECOMMENDED,
                'hr_approved' => LeaveRequest::STATUS_HR_APPROVED,
                'approved' => LeaveRequest::STATUS_APPROVED,
                'rejected' => LeaveRequest::STATUS_DISAPPROVED,
                'cancelled' => LeaveRequest::STATUS_CANCELLED
            ];
            $query->where('status', $statusMap[$status]);
        }

        if ($leaveType !== 'all') {
            $query->where('leave_type', $leaveType);
        }

        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }

        // Apply search
        if (!empty($search)) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $leaveRequests = $query->paginate(10)->withQueryString();

        return view('department_leave_requests', [
            'leaveRequests' => $leaveRequests,
            'filters' => [
                'status' => $status,
                'leave_type' => $leaveType,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'search' => $search
            ]
        ]);
    }

    /**
     * Get recent leave requests for the mayor dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function mayorDashboard()
    {
        // Get recent leave requests eligible for mayor (HR approved) and already approved by mayor (including cancelled)
        $leaveRequests = LeaveRequest::with('user')
            ->whereIn('status', [LeaveRequest::STATUS_HR_APPROVED, LeaveRequest::STATUS_APPROVED, LeaveRequest::STATUS_CANCELLED])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get overall statistics
        $stats = [
            'pending' => LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->count(),
            'approved_this_month' => LeaveRequest::where('status', LeaveRequest::STATUS_APPROVED)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'rejected_this_month' => LeaveRequest::where('status', LeaveRequest::STATUS_DISAPPROVED)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'total_employees' => User::count()
        ];

        return view('mayor_dashboard', [
            'leaveRequests' => $leaveRequests,
            'stats' => $stats
        ]);
    }

    /**
     * Display mayor leave requests with filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function mayorLeaveRequests(Request $request)
    {
        // Get filter parameters
        $status = $request->input('status', 'all');
        $leaveType = $request->input('leave_type', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search', '');

        // Start building the query (for all users)
        $query = LeaveRequest::with(['user', 'user.department'])
            ->whereIn('status', [LeaveRequest::STATUS_HR_APPROVED, LeaveRequest::STATUS_APPROVED, LeaveRequest::STATUS_CANCELLED])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($status !== 'all') {
            $statusMap = [
                'pending' => LeaveRequest::STATUS_PENDING,
                'hr_approved' => LeaveRequest::STATUS_HR_APPROVED,
                'approved' => LeaveRequest::STATUS_APPROVED,
                'rejected' => LeaveRequest::STATUS_DISAPPROVED,
                'cancelled' => LeaveRequest::STATUS_CANCELLED
            ];
            $query->where('status', $statusMap[$status]);
        }

        if ($leaveType !== 'all') {
            $query->where('leave_type', $leaveType);
        }

        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }

        // Apply search
        if (!empty($search)) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $leaveRequests = $query->paginate(10)->withQueryString();

        return view('mayor_leave_requests', [
            'leaveRequests' => $leaveRequests,
            'filters' => [
                'status' => $status,
                'leave_type' => $leaveType,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'search' => $search
            ]
        ]);
    }

    /**
     * Show the mayor approval view (HR-approved only)
     */
    public function showMayorApproval($id)
    {
        $leaveRequest = LeaveRequest::with(['user', 'user.department', 'recommendations', 'approvals'])
            ->findOrFail($id);
            
        // Allow viewing HR-approved requests and already mayor-approved requests
        if ($leaveRequest->status !== LeaveRequest::STATUS_HR_APPROVED && 
            $leaveRequest->status !== LeaveRequest::STATUS_APPROVED) {
            abort(403, 'This request is not eligible for mayor approval.');
        }
        
        return view('mayor_leave_approve', compact('leaveRequest'));
    }

    /**
     * Process mayor final decision
     */
    public function processMayorApproval(Request $request, $id)
    {
        $validated = $request->validate([
            'decision' => 'required|in:approve,disapprove',
            'comments' => 'nullable|string|max:500',
        ]);

        $leaveRequest = LeaveRequest::with('user')->findOrFail($id);
        if ($leaveRequest->status !== LeaveRequest::STATUS_HR_APPROVED) {
            return back()->with('error', 'Request not HR-approved yet.');
        }

        $leaveRequest->status = $validated['decision'] === 'approve'
            ? LeaveRequest::STATUS_APPROVED
            : LeaveRequest::STATUS_DISAPPROVED;
        $leaveRequest->save();

        // Send notification to the employee if they have enabled notifications
        if ($leaveRequest->user->notificationPreferences && 
            (($validated['decision'] === 'approve' && $leaveRequest->user->notificationPreferences->in_app_approvals) ||
             ($validated['decision'] === 'disapprove' && $leaveRequest->user->notificationPreferences->in_app_rejections))) {
            $leaveRequest->user->notify(new LeaveRequestStatusUpdated($leaveRequest));
        }
        
        // Notify all involved users about the final decision
        $involvedUsers = [];
        
        // Add the employee who submitted the request
        $involvedUsers[] = $leaveRequest->user;
        
        // Add department admin(s) who recommended the request
        $departmentAdmins = User::whereIn('user_id', $leaveRequest->recommendations->pluck('department_admin_id'))->get();
        $involvedUsers = array_merge($involvedUsers, $departmentAdmins->all());
        
        // Add HR manager(s) who approved the request
        $hrManagers = User::whereIn('user_id', $leaveRequest->approvals->pluck('hr_manager_id'))->get();
        $involvedUsers = array_merge($involvedUsers, $hrManagers->all());
        
        // Add mayor(s) if this is a mayor approval
        if ($validated['decision'] === 'approve') {
            $mayors = User::where('user_type', 'mayor')->get();
            $involvedUsers = array_merge($involvedUsers, $mayors->all());
        }
        
        // Remove duplicates and notify each user
        $notifiedUserIds = [];
        foreach ($involvedUsers as $user) {
            // Skip if we've already notified this user or if they don't have notifications enabled
            if (in_array($user->user_id, $notifiedUserIds) || 
                !$user->notificationPreferences || 
                !$user->notificationPreferences->in_app_approvals) {
                continue;
            }
            
            // Don't notify the employee again if we already did above
            if ($user->user_id === $leaveRequest->user->user_id) {
                continue;
            }
            
            // Notify the user
            $user->notify(new LeaveRequestStatusUpdated($leaveRequest));
            $notifiedUserIds[] = $user->user_id;
        }

        return redirect()->route('mayor.leave.requests')->with('success', 'Final decision recorded.');
    }
}
