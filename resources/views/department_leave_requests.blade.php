@php
use App\Models\LeaveRequest;
@endphp

<x-layouts.layout>
    <x-slot:title>Department Leave Requests</x-slot:title>
    <x-slot:header>Department Leave Requests</x-slot:header>
    
    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                <i class="fi-rr-list-check text-blue-500 mr-2"></i>
                Manage Leave Requests
            </h2>
            
            <!-- Filter Controls -->
            <form method="GET" action="{{ route('department.leave.requests') }}" class="flex flex-wrap gap-4 mb-6">
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
                        @foreach(LeaveRequest::LEAVE_TYPES as $type => $label)
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
                        @forelse($leaveRequests as $leaveRequest)
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
                                <td>{{ LeaveRequest::LEAVE_TYPES[$leaveRequest->leave_type] ?? $leaveRequest->leave_type }}</td>
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
                                    @if($leaveRequest->isPending())
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($leaveRequest->isApproved())
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($leaveRequest->isDisapproved())
                                        <span class="badge badge-error">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('department.leave.approve.start', $leaveRequest->id) }}" class="btn btn-xs btn-primary">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">
                                    No leave requests found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-end mt-6">
                <div class="btn-group">
                    @if($leaveRequests->onFirstPage())
                        <button class="btn btn-sm" disabled>«</button>
                    @else
                        <a href="{{ $leaveRequests->previousPageUrl() }}" class="btn btn-sm">«</a>
                    @endif

                    @for($i = 1; $i <= $leaveRequests->lastPage(); $i++)
                        @if($i == $leaveRequests->currentPage())
                            <button class="btn btn-sm btn-active">{{ $i }}</button>
                        @else
                            <a href="{{ $leaveRequests->url($i) }}" class="btn btn-sm">{{ $i }}</a>
                        @endif
                    @endfor

                    @if($leaveRequests->hasMorePages())
                        <a href="{{ $leaveRequests->nextPageUrl() }}" class="btn btn-sm">»</a>
                    @else
                        <button class="btn btn-sm" disabled>»</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Leave Details Modal -->
    <div class="modal" id="leaveDetailsModal">
        <div class="modal-box max-w-3xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">Leave Request Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost" onclick="closeLeaveModal()">✕</button>
            </div>
            
            <div class="mb-6">
                <!-- Employee Info -->
                <div class="flex items-center mb-4 pb-4 border-b border-gray-200">
                    <div class="avatar mr-4">
                        <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center">
                            <span class="text-white font-bold" id="employeeInitials">DP</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg" id="employeeName">Daniel Pombo</h4>
                        <p class="text-gray-600 text-sm">IT Specialist</p>
                    </div>
                </div>
                
                <!-- Leave Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h5 class="font-bold text-gray-700 mb-2">Leave Information</h5>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Leave Type</p>
                                    <p class="font-medium" id="leaveType">Vacation Leave</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <span class="badge badge-warning">Pending</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Applied On</p>
                                    <p class="font-medium">May 15, 2023</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Leave Period</p>
                                    <p class="font-medium">Jun 1-5, 2023</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">No. of Days</p>
                                    <p class="font-medium">5</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Remaining Balance</p>
                                    <p class="font-medium">10 days</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-bold text-gray-700 mb-2">Reason for Leave</h5>
                        <div class="bg-gray-50 p-4 rounded-lg h-full">
                            <p class="text-gray-700">
                                I am planning a family vacation to visit relatives. This has been planned for several months and all arrangements have been made.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Attachments -->
                <div class="mb-6">
                    <h5 class="font-bold text-gray-700 mb-2">Attachments</h5>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <i class="fi-rr-file-pdf text-red-500"></i>
                            <span class="text-blue-500 hover:underline cursor-pointer">flight_itinerary.pdf</span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button class="btn btn-outline" onclick="closeLeaveModal()">Close</button>
                    <button class="btn btn-error">
                        <i class="fi-rr-cross mr-2"></i>
                        Reject
                    </button>
                    <button class="btn btn-success" onclick="window.location.href='{{ route('department.leave.approve.start', ['id' => 1]) }}'">
                        <i class="fi-rr-check mr-2"></i>
                        Approve
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Open leave details modal
        function openLeaveModal(name, leaveType) {
            document.getElementById('employeeName').textContent = name;
            document.getElementById('leaveType').textContent = leaveType;
            document.getElementById('employeeInitials').textContent = name.split(' ').map(n => n[0]).join('');
            document.getElementById('leaveDetailsModal').classList.add('modal-open');
        }
        
        // Close leave details modal
        function closeLeaveModal() {
            document.getElementById('leaveDetailsModal').classList.remove('modal-open');
        }
    </script>
</x-layouts.layout>


