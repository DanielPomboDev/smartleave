@php
use App\Models\LeaveRequest;
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
            <form method="GET" action="{{ route('mayor.leave.requests') }}" class="flex flex-wrap gap-4 mb-6" id="filterForm">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Filter by Status</span>
                    </label>
                    <select name="status" class="select select-bordered border-gray-300 focus:border-blue-500" onchange="this.form.submit()">
                        <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="hr_approved" {{ $filters['status'] === 'hr_approved' ? 'selected' : '' }}>HR Approved</option>
                        <option value="approved" {{ $filters['status'] === 'approved' ? 'selected' : '' }}>Mayor Approved</option>
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
                        <span class="label-text font-medium text-gray-700">Search Employee</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               id="searchInput"
                               class="input input-bordered border-gray-300 focus:border-blue-500 w-full" 
                               value="{{ $filters['search'] }}" 
                               placeholder="Type employee name..."
                               autocomplete="off">
                    </div>
                </div>
            </form>
            
            <!-- Search Results Summary -->
            @if(!empty($filters['search']) || $filters['status'] !== 'all' || $filters['leave_type'] !== 'all')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="fi-rr-filter text-blue-500"></i>
                            <span class="text-sm font-medium text-blue-700">Active Filters:</span>
                        </div>
                        <a href="{{ route('mayor.leave.requests') }}" class="text-sm text-blue-600 hover:text-blue-800 underline">
                            Clear All Filters
                        </a>
                    </div>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @if(!empty($filters['search']))
                            <span class="badge badge-primary badge-outline">
                                Search: "{{ $filters['search'] }}"
                            </span>
                        @endif
                        @if($filters['status'] !== 'all')
                            <span class="badge badge-primary badge-outline">
                                Status: {{ ucfirst(str_replace('_', ' ', $filters['status'])) }}
                            </span>
                        @endif
                        @if($filters['leave_type'] !== 'all')
                            <span class="badge badge-primary badge-outline">
                                Type: {{ LeaveRequest::LEAVE_TYPES[$filters['leave_type']] ?? $filters['leave_type'] }}
                            </span>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Leave Requests Table -->
            <div class="overflow-x-auto">
                <!-- Results Summary -->
                <div class="flex justify-between items-center mb-4">
                    <div class="text-sm text-gray-600">
                        @if($leaveRequests->total() > 0)
                            Showing {{ $leaveRequests->firstItem() ?? 0 }} to {{ $leaveRequests->lastItem() ?? 0 }} of {{ $leaveRequests->total() }} results
                            @if(!empty($filters['search']))
                                for "<strong>{{ $filters['search'] }}</strong>"
                            @endif
                        @else
                            No results found
                            @if(!empty($filters['search']))
                                for "<strong>{{ $filters['search'] }}</strong>"
                            @endif
                        @endif
                    </div>
                    @if($leaveRequests->total() > 0)
                        <div class="text-sm text-gray-500">
                            Page {{ $leaveRequests->currentPage() }} of {{ $leaveRequests->lastPage() }}
                        </div>
                    @endif
                </div>

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
                            <tr class="hover:bg-gray-50 transition-colors">
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
                                    @if($leaveRequest->status === LeaveRequest::STATUS_HR_APPROVED)
                                        <span class="badge badge-primary">HR Approved</span>
                                    @elseif($leaveRequest->status === LeaveRequest::STATUS_APPROVED)
                                        <span class="badge badge-success">Mayor Approved</span>
                                    @elseif($leaveRequest->status === LeaveRequest::STATUS_DISAPPROVED)
                                        <span class="badge badge-error">Rejected</span>
                                    @else
                                        <span class="badge badge-ghost">{{ ucfirst($leaveRequest->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('mayor.leave.approve.start', $leaveRequest->id) }}" class="btn btn-xs btn-primary hover:btn-primary-focus transition-colors">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <div class="flex flex-col items-center space-y-3">
                                        <i class="fi-rr-search text-4xl text-gray-300"></i>
                                        <div class="text-gray-500">
                                            @if(!empty($filters['search']))
                                                No leave requests found for "<strong>{{ $filters['search'] }}</strong>"
                                            @else
                                                No leave requests found
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            Try adjusting your search criteria or filters
                                        </div>
                                        @if(!empty($filters['search']) || $filters['status'] !== 'all' || $filters['leave_type'] !== 'all')
                                            <a href="{{ route('mayor.leave.requests') }}" class="btn btn-sm btn-outline">
                                                Clear All Filters
                                            </a>
                                        @endif
                                    </div>
                                </td>
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
    
    <script>
        // Handle Enter key press for search
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            
            if (searchInput) {
                // Handle Enter key press
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('filterForm').submit();
                    }
                });
            }
        });
    </script>
</x-layouts.layout>