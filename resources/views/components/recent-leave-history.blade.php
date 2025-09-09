<div class="card bg-white shadow-md">
    <div class="card-body">
        <div class="flex justify-between items-center mb-4">
            <h2 class="card-title text-xl font-bold text-gray-800">
                <i class="fi-rr-time-past text-blue-500 mr-2"></i>
                Recent Leave History
            </h2>
            
            <a href="{{ route('employee.leave.history') }}" class="btn btn-sm btn-outline inline-flex items-center">
                View All History
                <i class="fi-rr-arrow-right"></i>
            </a>
        </div>
        
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
                                @elseif($leaveRequest->isCancelled())
                                    <span class="badge badge-neutral">Cancelled</span>
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
    </div>
</div>
