@php
// Sample static data for UI demonstration
$filters = [
    'status' => 'all',
    'leave_type' => 'all',
    'start_date' => '',
    'end_date' => '',
    'search' => ''
];

$leaveRequests = [
    (object) [
        'user' => (object) [
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'position' => 'IT Specialist'
        ],
        'leave_type' => 'vacation',
        'created_at' => now(),
        'start_date' => now(),
        'end_date' => now()->addDays(2),
        'number_of_days' => 3,
        'status' => 'pending',
        'id' => 1
    ],
    (object) [
        'user' => (object) [
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'position' => 'HR Officer'
        ],
        'leave_type' => 'sick',
        'created_at' => now()->subDays(10),
        'start_date' => now()->subDays(10),
        'end_date' => now()->subDays(8),
        'number_of_days' => 3,
        'status' => 'approved',
        'id' => 2
    ],
    (object) [
        'user' => (object) [
            'first_name' => 'Pedro',
            'last_name' => 'Reyes',
            'position' => 'Finance Clerk'
        ],
        'leave_type' => 'emergency',
        'created_at' => now()->subDays(5),
        'start_date' => now()->subDays(5),
        'end_date' => now()->subDays(3),
        'number_of_days' => 3,
        'status' => 'rejected',
        'id' => 3
    ]
];

$leaveTypes = [
    'vacation' => 'Vacation Leave',
    'sick' => 'Sick Leave',
    'emergency' => 'Emergency Leave'
];
@endphp

<x-layouts.layout>
    <x-slot:title>Mayor Leave Requests</x-slot:title>
    <x-slot:header>Mayor Leave Requests</x-slot:header>
    
    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                <i class="fi-rr-list-check text-blue-500 mr-2"></i>
                Manage Leave Requests
            </h2>
            
            <!-- Filter Controls -->
            <form method="GET" action="#" class="flex flex-wrap gap-4 mb-6">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Filter by Status</span>
                    </label>
                    <select name="status" class="select select-bordered border-gray-300 focus:border-blue-500" onchange="this.form.submit()">
                        <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending" {{ $filters['status'] === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $filters['status'] === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $filters['status'] === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Filter by Leave Type</span>
                    </label>
                    <select name="leave_type" class="select select-bordered border-gray-300 focus:border-blue-500" onchange="this.form.submit()">
                        <option value="all" {{ $filters['leave_type'] === 'all' ? 'selected' : '' }}>All Types</option>
                        @foreach($leaveTypes as $type => $label)
                            <option value="{{ $type }}" {{ $filters['leave_type'] === $type ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Date Range</span>
                    </label>
                    <div class="flex space-x-2">
                        <input type="date" name="start_date" class="input input-bordered border-gray-300 focus:border-blue-500" value="{{ $filters['start_date'] }}" placeholder="From">
                        <input type="date" name="end_date" class="input input-bordered border-gray-300 focus:border-blue-500" value="{{ $filters['end_date'] }}" placeholder="To">
                    </div>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Search</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="search" class="input input-bordered border-gray-300 focus:border-blue-500 w-full pr-10" value="{{ $filters['search'] }}" placeholder="Search by name...">
                        <button type="submit" class="absolute inset-y-0 right-0 px-3 flex items-center">
                            <i class="fi-rr-search text-gray-400"></i>
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Leave Requests Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-gray-600">Employee</th>
                            <th class="text-gray-600">Leave Type</th>
                            <th class="text-gray-600">Applied On</th>
                            <th class="text-gray-600">Period</th>
                            <th class="text-gray-600">Days</th>
                            <th class="text-gray-600">Status</th>
                            <th class="text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests as $leaveRequest)
                            <tr>
                                <td class="flex items-center space-x-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle w-8 h-8">
                                            <span class="bg-blue-500 text-white text-xs font-bold flex items-center justify-center w-full h-full">
                                                {{ strtoupper(substr($leaveRequest->user->first_name, 0, 1) . substr($leaveRequest->user->last_name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $leaveRequest->user->first_name }} {{ $leaveRequest->user->last_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $leaveRequest->user->position }}</div>
                                    </div>
                                </td>
                                <td>{{ $leaveTypes[$leaveRequest->leave_type] ?? $leaveRequest->leave_type }}</td>
                                <td>{{ $leaveRequest->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($leaveRequest->start_date->isSameDay($leaveRequest->end_date))
                                        {{ $leaveRequest->start_date->format('M d, Y') }}
                                    @else
                                        {{ $leaveRequest->start_date->format('M d') }}-{{ $leaveRequest->end_date->format('d, Y') }}
                                    @endif
                                </td>
                                <td>{{ $leaveRequest->number_of_days }}</td>
                                <td>
                                    @if($leaveRequest->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($leaveRequest->status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($leaveRequest->status === 'rejected')
                                        <span class="badge badge-error">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('mayor.leave.approve.start', $leaveRequest->id) }}" class="btn btn-xs btn-primary">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.layout> 