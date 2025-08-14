<x-layouts.layout>
    <x-slot:title>Leave Requests</x-slot:title>
    <x-slot:header>Leave Requests</x-slot:header>
    
    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                <i class="fi-rr-list-check text-blue-500 mr-2"></i>
                Manage Leave Requests
            </h2>
            
            <!-- Filter Controls -->
            <form method="GET" action="{{ route('hr.leave.requests') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Status</span>
                    </label>
                    <select name="status" class="select select-bordered border-gray-300 focus:border-blue-500 w-full" onchange="this.form.submit()">
                        <option value="all" {{ (isset($filters['status']) && $filters['status'] === 'all') ? 'selected' : '' }}>All Status</option>
                        <option value="{{ \App\Models\LeaveRequest::STATUS_PENDING }}" {{ (isset($filters['status']) && $filters['status'] === \App\Models\LeaveRequest::STATUS_PENDING) ? 'selected' : '' }}>Pending</option>
                        <option value="{{ \App\Models\LeaveRequest::STATUS_RECOMMENDED }}" {{ (isset($filters['status']) && $filters['status'] === \App\Models\LeaveRequest::STATUS_RECOMMENDED) ? 'selected' : '' }}>Recommended</option>
                        <option value="{{ \App\Models\LeaveRequest::STATUS_HR_APPROVED }}" {{ (isset($filters['status']) && $filters['status'] === \App\Models\LeaveRequest::STATUS_HR_APPROVED) ? 'selected' : '' }}>HR Approved</option>
                        <option value="{{ \App\Models\LeaveRequest::STATUS_APPROVED }}" {{ (isset($filters['status']) && $filters['status'] === \App\Models\LeaveRequest::STATUS_APPROVED) ? 'selected' : '' }}>Approved</option>
                        <option value="{{ \App\Models\LeaveRequest::STATUS_DISAPPROVED }}" {{ (isset($filters['status']) && $filters['status'] === \App\Models\LeaveRequest::STATUS_DISAPPROVED) ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Department</span>
                    </label>
                    <select name="department" class="select select-bordered border-gray-300 focus:border-blue-500 w-full" onchange="this.form.submit()">
                        <option value="all" {{ (isset($filters['department']) && $filters['department'] === 'all') ? 'selected' : '' }}>All Departments</option>
                        @if(isset($departments))
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ (isset($filters['department']) && $filters['department'] == $dept->id) ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Date Range</span>
                    </label>
                    <select name="date_range" class="select select-bordered border-gray-300 focus:border-blue-500 w-full" onchange="this.form.submit()">
                        <option value="all" {{ (isset($filters['date_range']) && $filters['date_range'] === 'all') ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ (isset($filters['date_range']) && $filters['date_range'] === 'today') ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ (isset($filters['date_range']) && $filters['date_range'] === 'week') ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ (isset($filters['date_range']) && $filters['date_range'] === 'month') ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Search</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="search" placeholder="Search employee name" value="{{ $filters['search'] ?? '' }}" class="input input-bordered border-gray-300 focus:border-blue-500 w-full pr-10">
                        <button class="absolute right-2 top-1/2 -translate-y-1/2" type="submit">
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
                                        <div class="text-xs text-gray-500">{{ $leaveRequest->user->department->name ?? '' }}</div>
                                    </div>
                                </td>
                                <td>{{ \App\Models\LeaveRequest::LEAVE_TYPES[$leaveRequest->leave_type] ?? $leaveRequest->leave_type }}</td>
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
                                    @if($leaveRequest->status === \App\Models\LeaveRequest::STATUS_PENDING)
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($leaveRequest->status === \App\Models\LeaveRequest::STATUS_RECOMMENDED)
                                        <span class="badge badge-info">Recommended</span>
                                    @elseif($leaveRequest->status === \App\Models\LeaveRequest::STATUS_HR_APPROVED)
                                        <span class="badge badge-primary">HR Approved</span>
                                    @elseif($leaveRequest->status === \App\Models\LeaveRequest::STATUS_APPROVED)
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($leaveRequest->status === \App\Models\LeaveRequest::STATUS_DISAPPROVED)
                                        <span class="badge badge-error">Rejected</span>
                                    @else
                                        <span class="badge">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('leave.approve.start', ['id' => $leaveRequest->id]) }}" class="btn btn-xs btn-primary">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">No leave requests found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-center mt-6">
                <div class="pagination">
                    {{ $leaveRequests->appends(request()->query())->links('pagination::simple-tailwind') }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Leave Request Modal -->
    <div class="modal" id="leaveRequestModal">
        <div class="modal-box max-w-3xl">
            <h3 class="font-bold text-lg" id="modalTitle">Leave Request Details</h3>
            <button class="btn btn-sm btn-circle absolute right-2 top-2" onclick="closeLeaveModal()">✕</button>
            
            <div class="py-4">
                <!-- Employee Info -->
                <div class="flex items-center mb-6">
                    <div class="avatar mr-4">
                        <div class="w-16 rounded-full">
                            <div class="bg-blue-500 text-white text-lg font-bold flex items-center justify-center w-full h-full" id="employeeInitials">DP</div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold" id="employeeName">Daniel Pombo</h4>
                        <p class="text-gray-600">IT Department • Software Developer</p>
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
                                    <p class="text-sm text-gray-500">Half/Full Day</p>
                                    <p class="font-medium">Full Day</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-bold text-gray-700 mb-2">Leave Balance</h5>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Vacation Leave</p>
                                    <p class="font-medium">15 days</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Sick Leave</p>
                                    <p class="font-medium">12 days</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Emergency Leave</p>
                                    <p class="font-medium">3 days</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Remaining After</p>
                                    <p class="font-medium text-orange-500">10 days</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Reason and Attachments -->
                <div class="mb-6">
                    <h5 class="font-bold text-gray-700 mb-2">Reason for Leave</h5>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p>I am planning a family vacation to Boracay. This is a long-planned trip and all arrangements have been made. I have completed all pending tasks and have briefed my team about ongoing projects.</p>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h5 class="font-bold text-gray-700 mb-2">Attachments</h5>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fi-rr-file-pdf text-red-500 mr-2"></i>
                            <span>Flight_Booking.pdf</span>
                            <a href="#" class="ml-auto text-blue-500 hover:underline">
                                <i class="fi-rr-download"></i>
                                </a>
                        </div>
                    </div>
                </div>
                
                <!-- HR Action -->
                <div class="border-t border-gray-200 pt-6">
                    <h5 class="font-bold text-gray-700 mb-4">HR Action</h5>
                    
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-medium">Comments (Optional)</span>
                        </label>
                        <textarea class="textarea textarea-bordered h-24" placeholder="Add your comments here..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button class="btn btn-error">
                            <i class="fi-rr-cross mr-2"></i>
                            Reject
                        </button>
                        <button class="btn btn-success">
                            <i class="fi-rr-check mr-2"></i>
                            Approve
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Show/hide custom date range based on selection
        document.addEventListener('DOMContentLoaded', function() {
            const dateRangeSelect = document.querySelector('select[class*="select"]');
            const customDateRange = document.getElementById('customDateRange');
            
            if (dateRangeSelect && customDateRange) {
                dateRangeSelect.addEventListener('change', function() {
                    if (this.value === 'custom') {
                        customDateRange.classList.remove('hidden');
                    } else {
                        customDateRange.classList.add('hidden');
                    }
                });
            }
        });
        
        // Modal functions
        function openLeaveModal(name, leaveType) {
            const modal = document.getElementById('leaveRequestModal');
            const employeeName = document.getElementById('employeeName');
            const leaveTypeEl = document.getElementById('leaveType');
            const employeeInitials = document.getElementById('employeeInitials');
            
            if (modal && employeeName && leaveTypeEl && employeeInitials) {
                employeeName.textContent = name;
                leaveTypeEl.textContent = leaveType;
                
                // Generate initials
                const initials = name.split(' ').map(n => n[0]).join('');
                employeeInitials.textContent = initials;
                
                // Show modal
                modal.classList.add('modal-open');
            }
        }
        
        function closeLeaveModal() {
            const modal = document.getElementById('leaveRequestModal');
            if (modal) {
                modal.classList.remove('modal-open');
            }
        }
    </script>
</x-layouts.layout>


