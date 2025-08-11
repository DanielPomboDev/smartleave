@php
// Sample static data for UI demonstration
$stats = [
    'pending' => 3,
    'approved_this_month' => 7,
    'rejected_this_month' => 2,
    'total_employees' => 25
];

$leaveRequests = [
    (object) [
        'user' => (object) [
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'position' => 'IT Specialist'
        ],
        'leave_type' => 'vacation',
        'start_date' => now(),
        'end_date' => now()->addDays(2),
        'number_of_days' => 3,
        'status' => 'pending',
        'isPending' => fn() => true,
        'isApproved' => fn() => false,
        'isDisapproved' => fn() => false,
        'id' => 1
    ],
    (object) [
        'user' => (object) [
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'position' => 'HR Officer'
        ],
        'leave_type' => 'sick',
        'start_date' => now()->subDays(10),
        'end_date' => now()->subDays(8),
        'number_of_days' => 3,
        'status' => 'approved',
        'isPending' => fn() => false,
        'isApproved' => fn() => true,
        'isDisapproved' => fn() => false,
        'id' => 2
    ],
    (object) [
        'user' => (object) [
            'first_name' => 'Pedro',
            'last_name' => 'Reyes',
            'position' => 'Finance Clerk'
        ],
        'leave_type' => 'emergency',
        'start_date' => now()->subDays(5),
        'end_date' => now()->subDays(3),
        'number_of_days' => 3,
        'status' => 'rejected',
        'isPending' => fn() => false,
        'isApproved' => fn() => false,
        'isDisapproved' => fn() => true,
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
    <x-slot:title>Mayor Dashboard</x-slot:title>
    <x-slot:header>Mayor Dashboard</x-slot:header>
    
    <!-- Overview Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Pending Requests Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Pending Requests</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['pending'] }}</h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fi-rr-hourglass-end text-xl text-blue-500"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Approved This Month Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600">Approved This Month</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['approved_this_month'] }}</h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fi-rr-check-circle text-xl text-green-500"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Rejected This Month Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-600">Rejected This Month</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['rejected_this_month'] }}</h3>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fi-rr-cross-circle text-xl text-red-500"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Employees Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">Total Employees</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_employees'] }}</h3>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fi-rr-users text-xl text-purple-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Leave Requests Table -->
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title text-xl font-bold text-gray-800">
                    <i class="fi-rr-time-past text-blue-500 mr-2"></i>
                    Recent Leave Requests
                </h2>
                
                <a href="{{ route('mayor.leave.requests') }}" class="btn btn-sm btn-outline">
                    View All Requests
                    <i class="fi-rr-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-gray-600">Employee</th>
                            <th class="text-gray-600">Leave Type</th>
                            <th class="text-gray-600">Period</th>
                            <th class="text-gray-600">No. of Days</th>
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