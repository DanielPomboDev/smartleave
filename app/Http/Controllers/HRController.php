<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class HRController extends Controller
{
    /**
     * Display a list of employees with filtering and search.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function employees(Request $request)
    {
        // Get filter parameters
        $department = $request->input('department', 'all');
        $position = $request->input('position', 'all');
        $search = $request->input('search', '');

        // Start building the query
        $query = User::with('department');

        // Apply filters
        if ($department !== 'all') {
            $query->where('department_id', $department);
        }

        if ($position !== 'all') {
            $query->where('position', $position);
        }

        // Apply search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('user_id', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $users = $query->paginate(10)->withQueryString();

        // Get all departments for the filter dropdown
        $departments = Department::all();

        // Get unique positions for the filter dropdown
        $positions = User::distinct()->pluck('position');

        return view('hr_employees', [
            'users' => $users,
            'departments' => $departments,
            'positions' => $positions,
            'filters' => [
                'department' => $department,
                'position' => $position,
                'search' => $search
            ]
        ]);
    }

    /**
     * HR dashboard: show recommended leave requests and summary stats
     */
    public function dashboard()
    {
        // HR queue: show recommended, HR-approved, and mayor-approved requests
        $hrQueue = LeaveRequest::with(['user.department'])
            ->whereIn('status', [LeaveRequest::STATUS_RECOMMENDED, LeaveRequest::STATUS_HR_APPROVED, LeaveRequest::STATUS_APPROVED])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Summary stats
        $stats = [
            'pending' => LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->count(),
            'approved_this_month' => LeaveRequest::where('status', LeaveRequest::STATUS_APPROVED)
                ->whereMonth('created_at', now()->month)->count(),
            'rejected_this_month' => LeaveRequest::where('status', LeaveRequest::STATUS_DISAPPROVED)
                ->whereMonth('created_at', now()->month)->count(),
            'total_employees' => \App\Models\User::count(),
        ];

        return view('hr_dashboard', compact('hrQueue', 'stats'));
    }

    /**
     * Store a newly created employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'user_id' => 'required|string|max:255|unique:users,user_id,NULL,user_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'email' => 'nullable|email|max:255',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'start_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'user_type' => 'required|in:employee,hr,department_admin',
        ]);

        // Create the user
        $user = new User();
        $user->user_id = $validated['user_id'];
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->middle_initial = $validated['middle_initial'];
        $user->email = $validated['email'] ?? null;
        $user->department_id = $validated['department_id'];
        $user->position = $validated['position'];
        $user->start_date = $validated['start_date'];
        $user->salary = $validated['salary'];
        $user->password = bcrypt('password'); // Default password
        $user->user_type = $validated['user_type'];
        $user->save();

        return redirect()->route('hr.employees')
            ->with('success', 'Employee added successfully.');
    }

    /**
     * Show the form for editing the specified employee.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        try {
            $user = User::where('user_id', $id)->firstOrFail();

            // Format the data for JSON response
            $data = [
                'user_id' => $user->user_id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'middle_initial' => $user->middle_initial,
                'email' => $user->email,
                'department_id' => $user->department_id,
                'position' => $user->position,
                'user_type' => $user->user_type,
                'start_date' => $user->start_date ? $user->start_date->format('Y-m-d') : null,
                'salary' => $user->salary
            ];

            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load employee data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified employee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'user_id' => 'required|string|max:255|unique:users,user_id,' . $id . ',user_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'email' => 'nullable|email|max:255',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'start_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'user_type' => 'required|in:employee,hr,department_admin',
        ]);

        // Find and update the user
        $user = User::where('user_id', $id)->firstOrFail();
        $user->user_id = $validated['user_id'];
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->middle_initial = $validated['middle_initial'];
        $user->email = $validated['email'] ?? null;
        $user->department_id = $validated['department_id'];
        $user->position = $validated['position'];
        $user->start_date = $validated['start_date'];
        $user->salary = $validated['salary'];
        $user->user_type = $validated['user_type'];
        $user->save();

        return redirect()->route('hr.employees')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Display a list of leave records with filtering and search.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function leaveRecords(Request $request)
    {
        // Get filter parameters
        $department = $request->input('department', 'all');
        $leaveType = $request->input('leave_type', 'all');
        $dateRange = $request->input('date_range', 'all');
        $search = $request->input('search', '');

        // Start building the query
        $query = LeaveRequest::with(['user.department'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($department !== 'all') {
            $query->whereHas('user', function ($q) use ($department) {
                $q->where('department_id', $department);
            });
        }

        if ($leaveType !== 'all') {
            $query->where('leave_type', $leaveType);
        }

        // Apply date range filter
        if ($dateRange !== 'all') {
            $now = now();
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', $now->month)
                        ->whereYear('created_at', $now->year);
                    break;
            }
        }

        // Apply search
        if (!empty($search)) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('user_id', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $leaveRecords = $query->paginate(10)->withQueryString();

        // Get all departments for the filter dropdown
        $departments = Department::all();

        return view('hr_leave_records', [
            'leaveRecords' => $leaveRecords,
            'departments' => $departments,
            'filters' => [
                'department' => $department,
                'leave_type' => $leaveType,
                'date_range' => $dateRange,
                'search' => $search
            ]
        ]);
    }

    public function showLeaveRecord($id)
    {
        // Get the employee with their department
        $employee = User::with('department')->findOrFail($id);
        
        // Get leave records for this employee, ordered by year/month descending
        $leaveRecords = \App\Models\LeaveRecord::where('user_id', $employee->user_id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->groupBy(function($item) {
                return $item->year;
            });
        
        return view('hr_leave_record', compact('employee', 'leaveRecords'));
    }

    /**
     * Display a list of leave requests with filtering and search.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function leaveRequests(Request $request)
    {
        // Get filter parameters
        $status = $request->input('status', 'all');
        $department = $request->input('department', 'all');
        $dateRange = $request->input('date_range', 'all');
        $search = $request->input('search', '');

        // Start building the query
        $query = LeaveRequest::with(['user.department'])
            ->orderBy('created_at', 'desc');

        // Apply status filter - by default show recommended and HR approved like dashboard
        if ($status === 'all') {
            $query->whereIn('status', [LeaveRequest::STATUS_RECOMMENDED, LeaveRequest::STATUS_HR_APPROVED]);
        } else if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Apply department filter
        if ($department !== 'all') {
            $query->whereHas('user', function ($q) use ($department) {
                $q->where('department_id', $department);
            });
        }

        // Apply date range filter
        if ($dateRange !== 'all') {
            $now = now();
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', $now->month)
                        ->whereYear('created_at', $now->year);
                    break;
            }
        }

        // Apply search
        if (!empty($search)) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('user_id', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $leaveRequests = $query->paginate(10)->withQueryString();

        // Get all departments for the filter dropdown
        $departments = Department::all();

        // Summary stats
        $stats = [
            'pending' => LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->count(),
            'approved' => LeaveRequest::where('status', LeaveRequest::STATUS_APPROVED)->count(),
            'disapproved' => LeaveRequest::where('status', LeaveRequest::STATUS_DISAPPROVED)->count(),
        ];

        return view('hr_leave_requests', [
            'leaveRequests' => $leaveRequests,
            'departments' => $departments,
            'stats' => $stats,
            'filters' => [
                'status' => $status,
                'department' => $department,
                'date_range' => $dateRange,
                'search' => $search
            ]
        ]);
    }
}
