<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
                'disapproved' => LeaveRequest::STATUS_DISAPPROVED
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
}
