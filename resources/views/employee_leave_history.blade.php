<x-layouts.layout>
    <x-slot:title>Leave History</x-slot:title>
    <x-slot:header>Leave History</x-slot:header>
    
    @if(session('success'))
    <div class="alert alert-success shadow-lg mb-6">
        <div>
            <i class="fi-rr-check text-success"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif
    
    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                <i class="fi-rr-time-past text-blue-500 mr-2"></i>
                Complete Leave History
            </h2>
            
            <!-- Filter Controls -->
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                    <select name="month" class="w-full select select-bordered border-gray-300 focus:border-blue-500" onchange="handleFilterChange(this)">
                        <option value="all" {{ ($filters['month'] ?? 'all') === 'all' ? 'selected' : '' }}>All Months</option>
                        @php
                            $months = [
                                '01' => 'January',
                                '02' => 'February',
                                '03' => 'March',
                                '04' => 'April',
                                '05' => 'May',
                                '06' => 'June',
                                '07' => 'July',
                                '08' => 'August',
                                '09' => 'September',
                                '10' => 'October',
                                '11' => 'November',
                                '12' => 'December'
                            ];
                            foreach ($months as $monthNum => $monthName) {
                                echo '<option value="' . $monthNum . '" ' . (($filters['month'] ?? 'all') == $monthNum ? 'selected' : '') . '>' . $monthName . '</option>';
                            }
                        @endphp
                    </select>
                </div>

                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Leave Type</label>
                    <select name="leave_type" class="w-full select select-bordered border-gray-300 focus:border-blue-500" onchange="handleFilterChange(this)">
                        <option value="all" {{ ($filters['leave_type'] ?? 'all') === 'all' ? 'selected' : '' }}>All Types</option>
                        @foreach(App\Models\LeaveRequest::LEAVE_TYPES as $type => $label)
                            <option value="{{ $type }}" {{ ($filters['leave_type'] ?? 'all') === $type ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full select select-bordered border-gray-300 focus:border-blue-500" onchange="handleFilterChange(this)">
                        <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ ($filters['status'] ?? 'all') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="recommended" {{ ($filters['status'] ?? 'all') === 'recommended' ? 'selected' : '' }}>Recommended</option>
                        <option value="hr_approved" {{ ($filters['status'] ?? 'all') === 'hr_approved' ? 'selected' : '' }}>HR Approved</option>
                        <option value="approved" {{ ($filters['status'] ?? 'all') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="disapproved" {{ ($filters['status'] ?? 'all') === 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                    </select>
                </div>
            </div>

            <script>
                function handleFilterChange(select) {
                    const params = new URLSearchParams(window.location.search);
                    params.set(select.name, select.value);
                    window.location.href = `${window.location.pathname}?${params.toString()}`;
                }
            </script>

            <!-- Leave History Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-gray-600">Date Filed</th>
                            <th class="text-gray-600">Leave Type</th>
                            <th class="text-gray-600">Period</th>
                            <th class="text-gray-600">No. of Days</th>
                            <th class="text-gray-600">Status</th>
                            <th class="text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $leaveRequest)
                            <tr>
                                <td>{{ $leaveRequest->created_at->format('M d, Y') }}</td>
                                <td>{{ App\Models\LeaveRequest::LEAVE_TYPES[$leaveRequest->leave_type] ?? $leaveRequest->leave_type }}</td>
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
                                    @elseif($leaveRequest->status === App\Models\LeaveRequest::STATUS_RECOMMENDED)
                                        <span class="badge badge-info">Recommended</span>
                                    @elseif($leaveRequest->status === App\Models\LeaveRequest::STATUS_HR_APPROVED)
                                        <span class="badge badge-primary">HR Approved</span>
                                    @elseif($leaveRequest->isApproved())
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($leaveRequest->isDisapproved())
                                        <span class="badge badge-error">Denied</span>
                                    @else
                                        <span class="badge">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('leave.view', $leaveRequest->id) }}" class="btn btn-xs bg-blue-600 text-white hover:bg-blue-700 border-none">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">
                                    No leave requests found
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
                </div>
            </div>
        </div>
    </div>
    <!-- Success Modal -->
    <div id="successModal" class="modal" style="display: none;">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-success">Success!</h3>
            <p id="successMessage" class="py-4">Your leave request has been submitted successfully and is pending approval.</p>
            <div class="modal-action">
                <button onclick="document.getElementById('successModal').style.display = 'none'" class="btn btn-primary">OK</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check for success parameter in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                console.log('Success parameter found in URL, showing success modal');
                const successModal = document.getElementById('successModal');
                const successMessage = document.getElementById('successMessage');
                successMessage.textContent = 'Your leave request has been submitted successfully and is pending approval.';
                successModal.style.display = 'flex';
            }
        });
    </script>
</x-layouts.layout>