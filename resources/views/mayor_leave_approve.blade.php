<x-layouts.layout>
    <x-slot:title>
        @if($leaveRequest->status === \App\Models\LeaveRequest::STATUS_HR_APPROVED)
            Final Approval - Leave Request
        @elseif($leaveRequest->status === \App\Models\LeaveRequest::STATUS_APPROVED)
            Approved - Leave Request
        @elseif($leaveRequest->status === \App\Models\LeaveRequest::STATUS_DISAPPROVED)
            Rejected - Leave Request
        @else
            Leave Request Details
        @endif
    </x-slot:title>
    <x-slot:header>
        @if($leaveRequest->status === \App\Models\LeaveRequest::STATUS_HR_APPROVED)
            <i class="fi-rr-check-circle text-green-500 mr-2"></i>
            Leave Final Approval
        @elseif($leaveRequest->status === \App\Models\LeaveRequest::STATUS_APPROVED)
            <i class="fi-rr-check-circle text-green-500 mr-2"></i>
            Leave Request Approved
        @elseif($leaveRequest->status === \App\Models\LeaveRequest::STATUS_DISAPPROVED)
            <i class="fi-rr-cross-circle text-red-500 mr-2"></i>
            Leave Request Rejected
        @else
            Leave Request Details
        @endif
    </x-slot:header>

    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                <i class="fi-rr-check-circle text-green-500 mr-2"></i>
                Leave Final Approval
            </h2>

            <!-- Leave Request Details -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 shadow-sm mb-6">
                <!-- Employee Info -->
                <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                    <div class="avatar mr-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                            <span class="text-white font-bold text-lg flex items-center justify-center w-full h-full">
                                {{ strtoupper(substr($leaveRequest->user->first_name, 0, 1) . substr($leaveRequest->user->last_name, 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">{{ $leaveRequest->user->first_name }} {{ $leaveRequest->user->last_name }}</h4>
                        <p class="text-gray-600">{{ $leaveRequest->user->department->name }} • {{ $leaveRequest->user->position }}</p>
                    </div>
                </div>

                <!-- Request Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Type of Leave</h5>
                        <p class="font-medium text-gray-800 text-lg">{{ ucfirst($leaveRequest->leave_type) }} Leave</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Applied On</h5>
                        <p class="font-medium text-gray-800 text-lg">{{ $leaveRequest->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Inclusive Dates</h5>
                        <p class="font-medium text-gray-800 text-lg">{{ $leaveRequest->start_date->format('M d, Y') }} - {{ $leaveRequest->end_date->format('M d, Y') }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Number of Working Days</h5>
                        <p class="font-medium text-gray-800 text-lg">{{ $leaveRequest->number_of_days }} days</p>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Where Leave Will Be Spent</h5>
                        <p class="font-medium text-gray-800">{{ $leaveRequest->where_spent }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Commutation</h5>
                        <p class="font-medium text-gray-800">{{ $leaveRequest->commutation ? 'Requested' : 'Not Requested' }}</p>
                    </div>
                </div>
            </div>

            <!-- Department Recommendation (first recommendation shown if exists) -->
            @php
                $deptRec = optional($leaveRequest->recommendations()->latest()->first());
            @endphp
            @if($deptRec)
            <div class="p-4 bg-white rounded-lg border border-blue-200 shadow-sm mb-6">
                <h4 class="font-semibold text-blue-600 mb-3">Department Admin Recommendation</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Authorized Personnel</label>
                        <p class="font-medium text-gray-800">{{ $deptRec->departmentAdmin->first_name ?? '' }} {{ $deptRec->departmentAdmin->last_name ?? '' }}</p>
                        <p class="text-sm text-gray-500">Department Head</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Decision</label>
                        <p class="font-medium text-gray-800 text-capitalize">{{ $deptRec->recommendation }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $deptRec->remarks }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- HR Approval Details (latest HR approval) -->
            @php
                $hrApproval = optional($leaveRequest->approvals()->latest()->first());
            @endphp
            @if($hrApproval)
            <div class="p-4 bg-white rounded-lg border border-green-200 shadow-sm mb-6">
                <h4 class="font-semibold text-green-600 mb-3">HR Manager Approval</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">HR Personnel</label>
                        <p class="font-medium text-gray-800">{{ $hrApproval->hrManager->first_name ?? '' }} {{ $hrApproval->hrManager->last_name ?? '' }}</p>
                        <p class="text-sm text-gray-500">HR Manager</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Decision</label>
                        <p class="font-medium text-gray-800 text-capitalize">{{ $hrApproval->approval }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            @if($hrApproval->approval === 'approve')
                                @if($hrApproval->approved_for === 'with_pay')
                                    Approved for days with pay
                                @elseif($hrApproval->approved_for === 'without_pay')
                                    Approved for days without pay
                                @else
                                    {{ $hrApproval->approved_for }}
                                @endif
                            @else
                                {{ $hrApproval->dissapproved_due_to }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Final Approval Action -->
            @if($leaveRequest->status === \App\Models\LeaveRequest::STATUS_HR_APPROVED)
                <form method="POST" action="{{ route('mayor.leave.approve.process', $leaveRequest->id) }}" class="flex justify-end mt-6">
                    @csrf
                    <input type="hidden" name="decision" id="decisionInput" value="approve" />
                    <input type="hidden" name="comments" id="commentsInput" />
                    <button type="submit" class="btn btn-success" onclick="document.getElementById('decisionInput').value='approve'">
                        <i class="fi-rr-check mr-2"></i>
                        Approve
                    </button>
                    <button type="submit" class="btn btn-error ml-2" onclick="document.getElementById('decisionInput').value='disapprove'">
                        <i class="fi-rr-cross mr-2"></i>
                        Reject
                    </button>
                </form>
            @elseif($leaveRequest->isCancelled())
                <div class="alert alert-warning mt-6">
                    <div class="flex items-center">
                        <i class="fi-rr-info text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-bold">Leave Request Cancelled</h3>
                            <p>This leave request has been cancelled by the employee and cannot be processed further.</p>
                        </div>
                    </div>
                </div>
            @elseif($leaveRequest->status === \App\Models\LeaveRequest::STATUS_APPROVED)
                <div class="alert alert-success mt-6">
                    <div class="flex items-center">
                        <i class="fi-rr-check-circle text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-bold">Leave Request Approved</h3>
                            <p>This leave request has been approved by the Mayor.</p>
                        </div>
                    </div>
                </div>
            @elseif($leaveRequest->status === \App\Models\LeaveRequest::STATUS_DISAPPROVED)
                <div class="alert alert-error mt-6">
                    <div class="flex items-center">
                        <i class="fi-rr-cross-circle text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-bold">Leave Request Rejected</h3>
                            <p>This leave request has been rejected by the Mayor.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.layout> 