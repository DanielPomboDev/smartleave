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
            <form method="GET" action="{{ route('department.leave.requests') }}" class="flex flex-wrap gap-4 mb-6" id="filterForm">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Filter by Status</span>
                    </label>
                    <select name="status" class="select select-bordered border-gray-300 focus:border-blue-500" onchange="this.form.submit()">
                        <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending" {{ $filters['status'] === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="recommended" {{ $filters['status'] === 'recommended' ? 'selected' : '' }}>Recommended</option>
                        <option value="hr_approved" {{ $filters['status'] === 'hr_approved' ? 'selected' : '' }}>HR Approved</option>
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
                        <a href="{{ route('department.leave.requests') }}" class="text-sm text-blue-600 hover:text-blue-800 underline">
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
                                Status: {{ ucfirst($filters['status']) }}
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
                                    @if($leaveRequest->isPending())
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($leaveRequest->isRecommended())
                                        <span class="badge badge-info">Recommended</span>
                                    @elseif($leaveRequest->isHrApproved())
                                        <span class="badge badge-primary">HR Approved</span>
                                    @elseif($leaveRequest->isApproved())
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($leaveRequest->isDisapproved())
                                        <span class="badge badge-error">Rejected</span>
                                    @else
                                        <span class="badge badge-ghost">{{ ucfirst($leaveRequest->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('department.leave.approve.start', $leaveRequest->id) }}" class="btn btn-xs btn-primary hover:btn-primary-focus transition-colors">
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
                                            <a href="{{ route('department.leave.requests') }}" class="btn btn-sm btn-outline">
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
            @if($leaveRequests->hasPages())
                <div class="flex justify-between items-center mt-6">
                    <div class="text-sm text-gray-600">
                        Showing {{ $leaveRequests->firstItem() ?? 0 }} to {{ $leaveRequests->lastItem() ?? 0 }} of {{ $leaveRequests->total() }} results
                    </div>
                    
                    <div class="btn-group">
                        @if($leaveRequests->onFirstPage())
                            <button class="btn btn-sm" disabled>«</button>
                        @else
                            <a href="{{ $leaveRequests->previousPageUrl() }}" class="btn btn-sm">«</a>
                        @endif

                        @foreach($leaveRequests->getUrlRange(1, $leaveRequests->lastPage()) as $page => $url)
                            @if($page == $leaveRequests->currentPage())
                                <button class="btn btn-sm btn-active">{{ $page }}</button>
                            @else
                                <a href="{{ $url }}" class="btn btn-sm">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($leaveRequests->hasMorePages())
                            <a href="{{ $leaveRequests->nextPageUrl() }}" class="btn btn-sm">»</a>
                        @else
                            <button class="btn btn-sm" disabled>»</button>
                        @endif
                    </div>
                </div>
            @endif
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
        // Add CSS for smooth animations
        const style = document.createElement('style');
        style.textContent = `
            .search-input-focus {
                transform: scale(1.02);
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            
            .filter-badge {
                transition: all 0.2s ease-in-out;
                cursor: pointer;
            }
            
            .filter-badge:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            
            .search-results-enter {
                opacity: 0;
                transform: translateY(-10px);
            }
            
            .search-results-enter-active {
                opacity: 1;
                transform: translateY(0);
                transition: all 0.3s ease-out;
            }
            
            .table-row-hover {
                transition: background-color 0.2s ease-in-out;
            }
            
            .loading-pulse {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.5; }
            }
        `;
        document.head.appendChild(style);

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

        // Function to reset all filters
        function resetFilters() {
            document.getElementById('filterForm').reset();
            document.getElementById('filterForm').submit();
        }

        <script>
        // Add CSS for smooth animations
        const style = document.createElement('style');
        style.textContent = `
            .search-input-focus {
                transform: scale(1.02);
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            
            .filter-badge {
                transition: all 0.2s ease-in-out;
                cursor: pointer;
            }
            
            .filter-badge:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            
            .search-results-enter {
                opacity: 0;
                transform: translateY(-10px);
            }
            
            .search-results-enter-active {
                opacity: 1;
                transform: translateY(0);
                transition: all 0.3s ease-out;
            }
            
            .table-row-hover {
                transition: background-color 0.2s ease-in-out;
            }
            
            .loading-pulse {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.5; }
            }
        `;
        document.head.appendChild(style);

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

        // Function to reset all filters
        function resetFilters() {
            document.getElementById('filterForm').reset();
            document.getElementById('filterForm').submit();
        }

        // Handle Enter key press for search
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            
            if (searchInput) {
                // Add focus effects
                searchInput.addEventListener('focus', function() {
                    this.parentElement.classList.add('search-input-focus');
                });
                
                searchInput.addEventListener('blur', function() {
                    this.parentElement.classList.remove('search-input-focus');
                });

                // Handle Enter key press
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('filterForm').submit();
                    }
                });
            }

            // Add visual feedback for active filters
            const activeFilters = document.querySelectorAll('.badge');
            activeFilters.forEach(badge => {
                badge.classList.add('filter-badge');
                badge.addEventListener('click', function() {
                    // Add a subtle animation when clicking on filter badges
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            });

            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.classList.add('table-row-hover');
            });
        });
    </script>
</x-layouts.layout>


