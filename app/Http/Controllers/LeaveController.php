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
        try {
            // Add debug logging
            \Log::info('Leave request submission started', [
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            // Validate the request
            $validated = $request->validate([
                'leaveType' => 'required|string|in:vacation,sick',
                'startDate' => 'required|date',
                'endDate' => 'required|date|after_or_equal:startDate',
                'numberOfDays' => 'required|integer|min:1',
                'locationType' => 'required|string',
                'location_specify' => 'nullable|string|max:255',
                'commutation' => 'nullable|string',
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
                        'maternity' => 'Maternity',
                        // Add other mappings as needed
                    ];
                    $subtype = $sickSubtypeMap[$request->input('sickSubtype')] ?? $request->input('sickSubtype');
                }
            }

            // Calculate number of days (in case it's different from what was submitted)
            $startDate = Carbon::parse($validated['startDate']);
            $endDate = Carbon::parse($validated['endDate']);
            $numberOfDays = $startDate->diffInDays($endDate) + 1; // Include both start and end dates

            // Check if employee has sufficient leave credits
            $hasSufficientCredits = $this->hasSufficientLeaveCredits($validated['leaveType'], $numberOfDays, Auth::id());
            \Log::info('Leave credits check', [
                'user_id' => Auth::id(),
                'leave_type' => $validated['leaveType'],
                'requested_days' => $numberOfDays,
                'has_sufficient_credits' => $hasSufficientCredits
            ]);
            
            if (!$hasSufficientCredits) {
                // Get the latest leave record for this user to determine their current balance
                $latestLeaveRecord = \App\Models\LeaveRecord::where('user_id', Auth::id())
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->first();
                    
                // If no record exists, use default values
                if (!$latestLeaveRecord) {
                    $vacationBalance = 15; // Default vacation balance
                    $sickBalance = 12;     // Default sick balance
                } else {
                    $vacationBalance = $latestLeaveRecord->vacation_balance;
                    $sickBalance = $latestLeaveRecord->sick_balance;
                }
                
                $availableCredits = $validated['leaveType'] === 'vacation' ? $vacationBalance : $sickBalance;
                
                // Instead of preventing submission, we'll add a flag to indicate insufficient credits
                // and show a warning message
                $request->session()->flash('warning', "Insufficient {$validated['leaveType']} leave credits. You have {$availableCredits} days available but are requesting {$numberOfDays} days. This leave will be considered without pay.");
                
                \Log::info('Insufficient leave credits warning set', [
                    'user_id' => Auth::id(),
                    'warning_message' => "Insufficient {$validated['leaveType']} leave credits. You have {$availableCredits} days available but are requesting {$numberOfDays} days. This leave will be considered without pay."
                ]);
            }

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
                $whereSpent = 'Outpatient: ' . $validated['location_specify'];
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
                $admin->notify(new LeaveRequestStatusUpdated($leaveRequest, 'new_request'));
            }

            // Redirect with success message - stay on the same page
            \Log::info('Leave request created successfully', [
                'user_id' => Auth::id(),
                'leave_request_id' => $leaveRequest->id
            ]);
            
            return redirect()->back()->with('success', 'Your leave request has been submitted successfully and is pending approval.');
        } catch (\Exception $e) {
            \Log::error('Error in leave request submission', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'An error occurred while submitting your leave request. Please try again.');
        }
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

            // Send notification to the employee who made the request
            $notificationType = $validated['recommendation'] === 'approve' ? 'recommended' : 'disapproved';
            $employee = $leaveRequest->user;
            $employee->notify(new LeaveRequestStatusUpdated($leaveRequest, $notificationType));

            // Send notification to HR users when request is recommended
            if ($validated['recommendation'] === 'approve') {
                $hrUsers = User::where('user_type', 'hr')->get();
                foreach ($hrUsers as $hrUser) {
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

        // Check if employee had sufficient leave credits when submitting the request
        $hasSufficientCredits = $this->hasSufficientLeaveCredits(
            $leaveRequest->leave_type, 
            $leaveRequest->number_of_days, 
            $leaveRequest->user_id
        );

        return view('hr_leave_approve', [
            'leaveId' => $id,
            'leaveRequest' => $leaveRequest,
            'hasSufficientCredits' => $hasSufficientCredits
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

            // Send notification to the employee
            $notificationType = $validated['approval'] === 'approve' ? 'hr_approved' : 'disapproved';
            $leaveRequest->user->notify(new LeaveRequestStatusUpdated($leaveRequest, $notificationType));
            
            // Send notification to department admins who recommended the request
            $departmentAdmins = User::whereIn('user_id', $leaveRequest->recommendations->pluck('department_admin_id'))
                ->where('user_type', 'department_admin')
                ->get();
                
            foreach ($departmentAdmins as $admin) {
                $admin->notify(new LeaveRequestStatusUpdated($leaveRequest, $notificationType));
            }
            
            // Notify mayor if the request was approved by HR
            if ($validated['approval'] === 'approve') {
                $mayors = User::where('user_type', 'mayor')->get();
                
                foreach ($mayors as $mayor) {
                    $mayor->notify(new LeaveRequestStatusUpdated($leaveRequest, 'hr_approved'));
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

        // If the leave request is approved by the mayor, record the leave
        if ($validated['decision'] === 'approve') {
            // Check if employee had sufficient credits when submitting the request
            $hasSufficientCredits = $this->hasSufficientLeaveCredits(
                $leaveRequest->leave_type, 
                $leaveRequest->number_of_days, 
                $leaveRequest->user_id
            );
            
            // Record the leave (always do this for approved leaves)
            $this->recordLeave($leaveRequest, $hasSufficientCredits);
        }

        // Send notification to the employee
        $notificationType = $validated['decision'] === 'approve' ? 'approved' : 'disapproved';
        $leaveRequest->user->notify(new LeaveRequestStatusUpdated($leaveRequest, $notificationType));
        
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
        
        // Notify all involved users (excluding the employee who already got notified)
        foreach ($involvedUsers as $user) {
            if ($user->user_id !== $leaveRequest->user->user_id) {
                $user->notify(new LeaveRequestStatusUpdated($leaveRequest, $notificationType));
            }
        }

        return redirect()->route('mayor.leave.requests')->with('success', 'Final decision recorded.');
    }

    /**
     * Check if employee has sufficient leave credits for a request
     *
     * @param string $leaveType
     * @param int $numberOfDays
     * @param int $userId
     * @return bool
     */
    private function hasSufficientLeaveCredits($leaveType, $numberOfDays, $userId)
    {
        // Get the latest leave record for this user to determine their current balance
        $latestLeaveRecord = \App\Models\LeaveRecord::where('user_id', $userId)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
            
        // If no record exists, use default values
        if (!$latestLeaveRecord) {
            $vacationBalance = 15; // Default vacation balance
            $sickBalance = 12;     // Default sick balance
        } else {
            $vacationBalance = $latestLeaveRecord->vacation_balance;
            $sickBalance = $latestLeaveRecord->sick_balance;
        }
        
        // Check if the user has sufficient credits
        if ($leaveType === 'vacation') {
            return $vacationBalance >= $numberOfDays;
        } elseif ($leaveType === 'sick') {
            return $sickBalance >= $numberOfDays;
        }
        
        return false;
    }

    /**
     * Deduct leave credits from the employee's record and record the leave
     *
     * @param LeaveRequest $leaveRequest
     * @return void
     */
    private function deductLeaveCredits(LeaveRequest $leaveRequest)
    {
        // Get the current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Get the most recent leave record for this user to determine their current balances
        $latestLeaveRecord = \App\Models\LeaveRecord::where('user_id', $leaveRequest->user_id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();

        // Calculate previous balances
        $previousVacationBalance = $latestLeaveRecord ? $latestLeaveRecord->vacation_balance : 0;
        $previousSickBalance = $latestLeaveRecord ? $latestLeaveRecord->sick_balance : 0;

        // Check if a leave record already exists for the current month
        $existingLeaveRecord = \App\Models\LeaveRecord::where([
            'user_id' => $leaveRequest->user_id,
            'month' => $currentMonth,
            'year' => $currentYear
        ])->first();

        if ($existingLeaveRecord) {
            // If a record already exists, use it
            $leaveRecord = $existingLeaveRecord;
        } else {
            // If no record exists, create a new one
            $leaveRecord = new \App\Models\LeaveRecord([
                'user_id' => $leaveRequest->user_id,
                'month' => $currentMonth,
                'year' => $currentYear,
                'vacation_earned' => 1.25,
                'sick_earned' => 1.25,
                'vacation_used' => 0,
                'sick_used' => 0,
                'vacation_balance' => $previousVacationBalance + 1.25,
                'sick_balance' => $previousSickBalance + 1.25,
                'undertime_hours' => 0,
                'vacation_entries' => [],
                'sick_entries' => []
            ]);
            $leaveRecord->save();
        }

        // Initialize vacation_entries and sick_entries as arrays if they are null
        $vacationEntries = $leaveRecord->vacation_entries ?? [];
        $sickEntries = $leaveRecord->sick_entries ?? [];

        // Format the leave entry
        $leaveEntry = [
            'start_date' => $leaveRequest->start_date->format('Y-m-d'),
            'end_date' => $leaveRequest->end_date->format('Y-m-d'),
            'days' => $leaveRequest->number_of_days,
            'type' => $leaveRequest->leave_type,
            'subtype' => $leaveRequest->subtype
        ];

        // Store the current used values to calculate the actual deduction
        $previousVacationUsed = $leaveRecord->vacation_used;
        $previousSickUsed = $leaveRecord->sick_used;

        // Deduct the appropriate leave credits based on leave type
        if ($leaveRequest->leave_type === 'vacation') {
            $leaveRecord->vacation_used += $leaveRequest->number_of_days;
            $vacationEntries[] = $leaveEntry;
        } elseif ($leaveRequest->leave_type === 'sick') {
            $leaveRecord->sick_used += $leaveRequest->number_of_days;
            $sickEntries[] = $leaveEntry;
        }

        // Update the entries arrays
        $leaveRecord->vacation_entries = $vacationEntries;
        $leaveRecord->sick_entries = $sickEntries;

        // Calculate the actual deduction that occurred
        $vacationDeduction = $leaveRecord->vacation_used - $previousVacationUsed;
        $sickDeduction = $leaveRecord->sick_used - $previousSickUsed;

        // Update balances based on the actual deduction
        $leaveRecord->vacation_balance -= $vacationDeduction;
        $leaveRecord->sick_balance -= $sickDeduction;

        // Save the updated leave record
        $leaveRecord->save();
    }

    /**
     * Record an approved leave request in the appropriate month's leave record
     * 
     * @param LeaveRequest $leaveRequest
     * @param bool $hasSufficientCredits
     * @return void
     */
    private function recordLeave(LeaveRequest $leaveRequest, bool $hasSufficientCredits)
    {
        // Get the month and year when the leave will be taken (not current date)
        $leaveMonth = $leaveRequest->start_date->month;
        $leaveYear = $leaveRequest->start_date->year;

        // Get the most recent leave record for this user to determine their current balances
        $latestLeaveRecord = \App\Models\LeaveRecord::where('user_id', $leaveRequest->user_id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();

        // Calculate previous balances
        $previousVacationBalance = $latestLeaveRecord ? $latestLeaveRecord->vacation_balance : 0;
        $previousSickBalance = $latestLeaveRecord ? $latestLeaveRecord->sick_balance : 0;

        // Check if a leave record already exists for the leave month
        $existingLeaveRecord = \App\Models\LeaveRecord::where([
            'user_id' => $leaveRequest->user_id,
            'month' => $leaveMonth,
            'year' => $leaveYear
        ])->first();

        if ($existingLeaveRecord) {
            // If a record already exists, use it
            $leaveRecord = $existingLeaveRecord;
        } else {
            // Create a new leave record with initial values
            // NOTE: Monthly credits are calculated at month end, not when individual leaves are approved
            $leaveRecord = new \App\Models\LeaveRecord([
                'user_id' => $leaveRequest->user_id,
                'month' => $leaveMonth,
                'year' => $leaveYear,
                'vacation_earned' => 0, // Will be calculated at month end
                'sick_earned' => 0, // Will be calculated at month end
                'vacation_used' => 0,
                'sick_used' => 0,
                'vacation_balance' => $previousVacationBalance,
                'sick_balance' => $previousSickBalance,
                'undertime_hours' => 0,
                'lwop_days' => 0,       // Days on leave without pay
                'vacation_entries' => [],
                'sick_entries' => []
            ]);
            $leaveRecord->save();
        }

        // Initialize vacation_entries and sick_entries as arrays if they are null
        $vacationEntries = $leaveRecord->vacation_entries ?? [];
        $sickEntries = $leaveRecord->sick_entries ?? [];

        // Format the leave entry
        $leaveEntry = [
            'start_date' => $leaveRequest->start_date->format('Y-m-d'),
            'end_date' => $leaveRequest->end_date->format('Y-m-d'),
            'days' => $leaveRequest->number_of_days,
            'type' => $leaveRequest->leave_type,
            'subtype' => $leaveRequest->subtype,
            'paid' => $hasSufficientCredits // Add information about whether it was paid or not
        ];

        // Store the current used values to calculate the actual deduction
        $previousVacationUsed = $leaveRecord->vacation_used;
        $previousSickUsed = $leaveRecord->sick_used;

        // Record the leave (deduct credits only if employee had sufficient credits AND it's for the current or past month)
        $isCurrentOrPastMonth = ($leaveYear < now()->year) || 
                               ($leaveYear == now()->year && $leaveMonth <= now()->month);
        
        if ($leaveRequest->leave_type === 'vacation') {
            // For future months, just record the entry without deducting leave
            // For current/past months, deduct immediately as before
            if ($hasSufficientCredits && $isCurrentOrPastMonth) {
                $leaveRecord->vacation_used += $leaveRequest->number_of_days;
                $leaveRecord->vacation_balance -= $leaveRequest->number_of_days;
            }
            $vacationEntries[] = $leaveEntry;
        } elseif ($leaveRequest->leave_type === 'sick') {
            // For future months, just record the entry without deducting leave
            // For current/past months, deduct immediately as before
            if ($hasSufficientCredits && $isCurrentOrPastMonth) {
                $leaveRecord->sick_used += $leaveRequest->number_of_days;
                $leaveRecord->sick_balance -= $leaveRequest->number_of_days;
            }
            $sickEntries[] = $leaveEntry;
        }

        // Update the entries arrays
        $leaveRecord->vacation_entries = $vacationEntries;
        $leaveRecord->sick_entries = $sickEntries;

        // Save the updated leave record
        $leaveRecord->save();
    }
}