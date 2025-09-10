<?php

namespace App\Http\Controllers;

use App\Models\LeaveRecord;
use App\Models\User;
use Illuminate\Http\Request;

class LeaveRecordController extends Controller
{
    /**
     * Display a listing of leave records.
     */
    public function index(Request $request)
    {
        $departments = \App\Models\Department::all();
        
        $filters = [
            'department' => $request->get('department', 'all'),
            'search' => $request->get('search', ''),
        ];
        
        // Get users with their information
        $query = User::with('department');
        
        // Apply department filter
        if ($filters['department'] !== 'all') {
            $query->where('department_id', $filters['department']);
        }
        
        // Apply search filter
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('last_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('user_id', 'like', '%' . $filters['search'] . '%');
            });
        }
        
        $users = $query->paginate(10);
        
        return view('hr_leave_records', compact('users', 'departments', 'filters'));
    }
    
    /**
     * Display the specified leave record.
     */
    public function show($userId)
    {
        $employee = User::with('department')->findOrFail($userId);
        
        // Get leave records for this employee, ordered by year/month descending
        $allLeaveRecords = LeaveRecord::where('user_id', $userId)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        $leaveRecords = $allLeaveRecords->groupBy(function($item) {
            return $item->year;
        });
        
        // Calculate summary totals
        $vacationSummary = [
            'earned' => $allLeaveRecords->sum('vacation_earned'),
            'used' => $allLeaveRecords->sum('vacation_used'),
            'balance' => $allLeaveRecords->isNotEmpty() ? $allLeaveRecords->first()->vacation_balance : 0 // Current balance
        ];
        
        $sickSummary = [
            'earned' => $allLeaveRecords->sum('sick_earned'),
            'used' => $allLeaveRecords->sum('sick_used'),
            'balance' => $allLeaveRecords->isNotEmpty() ? $allLeaveRecords->first()->sick_balance : 0 // Current balance
        ];
        
        return view('hr_leave_record', compact('employee', 'leaveRecords', 'vacationSummary', 'sickSummary'));
    }
    
    /**
     * Get leave records for a specific month/year for an employee.
     */
    public function getMonthlyRecord(Request $request, $userId)
    {
        $record = LeaveRecord::where('user_id', $userId)
            ->where('month', $request->get('month'))
            ->where('year', $request->get('year'))
            ->first();
            
        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        
        return response()->json($record);
    }
    
    /**
     * Store a newly created leave record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string|exists:users,user_id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
            'vacation_earned' => 'required|numeric|min:0',
            'vacation_used' => 'required|numeric|min:0',
            'sick_earned' => 'required|numeric|min:0',
            'sick_used' => 'required|numeric|min:0',
            'undertime_hours' => 'required|numeric|min:0',
            'vacation_entries' => 'nullable|array',
            'sick_entries' => 'nullable|array',
        ]);
        
        $record = LeaveRecord::create($validated);
        
        return response()->json($record, 201);
    }
    
    /**
     * Update the specified leave record.
     */
    public function update(Request $request, $id)
    {
        $record = LeaveRecord::findOrFail($id);
        
        $validated = $request->validate([
            'vacation_earned' => 'sometimes|numeric|min:0',
            'vacation_used' => 'sometimes|numeric|min:0',
            'sick_earned' => 'sometimes|numeric|min:0',
            'sick_used' => 'sometimes|numeric|min:0',
            'undertime_hours' => 'sometimes|numeric|min:0',
            'vacation_entries' => 'nullable|array',
            'sick_entries' => 'nullable|array',
        ]);
        
        $record->update($validated);
        
        // Recalculate balances
        $record->vacation_balance = $record->vacation_earned - $record->vacation_used;
        $record->sick_balance = $record->sick_earned - $record->sick_used;
        $record->save();
        
        return response()->json($record);
    }
    
    /**
     * Add undertime to a leave record.
     */
    public function addUndertime(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string|exists:users,user_id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
            'undertime_hours' => 'required|numeric|min:0|max:24',
        ]);
        
        try {
            // Round the undertime to 3 decimal places to match database precision
            $undertimeToAdd = round($validated['undertime_hours'], 3);
            
            // Check if a leave record already exists for this user/month/year
            $leaveRecord = LeaveRecord::where('user_id', $validated['user_id'])
                ->where('month', $validated['month'])
                ->where('year', $validated['year'])
                ->first();
            
            if ($leaveRecord) {
                // Update existing record by ADDING to the current undertime
                $newUndertime = round($leaveRecord->undertime_hours + $undertimeToAdd, 3);
                $leaveRecord->undertime_hours = $newUndertime;
                // Deduct undertime from vacation leave balance
                $leaveRecord->vacation_used = round($leaveRecord->vacation_used + $undertimeToAdd, 3);
                $leaveRecord->vacation_balance = round($leaveRecord->vacation_earned - $leaveRecord->vacation_used, 3);
                $leaveRecord->save();
            } else {
                // Create new record with default values
                // Deduct undertime from vacation leave balance
                $vacationUsed = round($undertimeToAdd, 3);
                $vacationBalance = round(1.25 - $vacationUsed, 3);
                
                $leaveRecord = LeaveRecord::create([
                    'user_id' => $validated['user_id'],
                    'month' => $validated['month'],
                    'year' => $validated['year'],
                    'vacation_earned' => 1.25,
                    'vacation_used' => $vacationUsed,
                    'vacation_balance' => $vacationBalance,
                    'sick_earned' => 1.25,
                    'sick_used' => 0,
                    'sick_balance' => 1.25,
                    'undertime_hours' => $undertimeToAdd,
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Undertime added successfully',
                'record' => $leaveRecord,
                'added_undertime' => $undertimeToAdd
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding undertime: ' . $e->getMessage()
            ], 500);
        }
    }
}