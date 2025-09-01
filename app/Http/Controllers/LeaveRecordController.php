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
        $leaveRecords = LeaveRecord::where('user_id', $userId)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->groupBy(function($item) {
                return $item->year;
            });
        
        return view('hr_leave_record', compact('employee', 'leaveRecords'));
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
}