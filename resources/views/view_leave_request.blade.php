<x-layouts.layout>
    <x-slot:title>Leave Request Details</x-slot:title>
    <x-slot:header>Leave Request Details</x-slot:header>

    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                <i class="fi-rr-eye text-blue-500 mr-2"></i>
                Leave Request Details
            </h2>

            <!-- Leave Request Details -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 shadow-sm mb-6">
                <!-- Employee Info -->
                <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                    <div class="avatar mr-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                            <span class="text-white font-bold text-lg flex items-center justify-center w-full h-full">
                                {{ substr($leaveRequest->user->first_name, 0, 1) }}{{ substr($leaveRequest->user->last_name, 0, 1) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">{{ $leaveRequest->user->full_name }}</h4>
                        <p class="text-gray-600">{{ $leaveRequest->user->department->name ?? 'Department' }} • {{ $leaveRequest->user->position }}</p>
                    </div>
                </div>

                <!-- Request Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Type of Leave</h5>
                        <p class="font-medium text-gray-800 text-lg">{{ App\Models\LeaveRequest::LEAVE_TYPES[$leaveRequest->leave_type] ?? $leaveRequest->leave_type }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Applied On</h5>
                        <p class="font-medium text-gray-800 text-lg">{{ $leaveRequest->created_at->format('F d, Y') }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Inclusive Dates</h5>
                        <p class="font-medium text-gray-800 text-lg">
                            @if($leaveRequest->start_date->isSameDay($leaveRequest->end_date))
                                {{ $leaveRequest->start_date->format('F d, Y') }}
                            @else
                                {{ $leaveRequest->start_date->format('F d') }} - {{ $leaveRequest->end_date->format('F d, Y') }}
                            @endif
                        </p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Number of Working Days</h5>
                        <p class="font-medium text-gray-800 text-lg">{{ $leaveRequest->number_of_days }} day(s)</p>
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

                <!-- Status -->
                <div class="mb-6">
                    <h5 class="font-semibold text-blue-600 mb-3">Status</h5>
                    @php($status = $leaveRequest->status)
                    @if($status === App\Models\LeaveRequest::STATUS_PENDING)
                        <span class="badge badge-warning">Pending</span>
                    @elseif($status === App\Models\LeaveRequest::STATUS_RECOMMENDED)
                        <span class="badge badge-info">Recommended</span>
                    @elseif($status === App\Models\LeaveRequest::STATUS_HR_APPROVED)
                        <span class="badge badge-primary">HR Approved</span>
                    @elseif($status === App\Models\LeaveRequest::STATUS_APPROVED)
                        <span class="badge badge-success">Approved</span>
                    @elseif($status === App\Models\LeaveRequest::STATUS_DISAPPROVED)
                        <span class="badge badge-error">Denied</span>
                    @elseif($status === App\Models\LeaveRequest::STATUS_CANCELLED)
                        <span class="badge badge-neutral">Cancelled</span>
                    @else
                        <span class="badge">—</span>
                    @endif
                </div>

                <!-- Cancel Button -->
                @if($leaveRequest->isCancellable())
                <div class="flex justify-end">
                    <button onclick="document.getElementById('cancelModal').showModal()" class="btn btn-error">
                        Cancel Leave Request
                    </button>
                </div>
                @elseif($leaveRequest->isFullyApproved())
                <div class="flex justify-end">
                    <button class="btn btn-disabled" disabled>
                        Cancel Leave Request (Approved)
                    </button>
                </div>
                @elseif($leaveRequest->isCancelled())
                <div class="flex justify-end">
                    <button class="btn btn-disabled" disabled>
                        Leave Request Cancelled
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Cancel Confirmation Modal -->
<dialog id="cancelModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg text-error">
            <i class="fi-rr-exclamation mr-2"></i>
            Confirm Cancellation
        </h3>
        <p class="py-4">
            Are you sure you want to cancel this leave request? This action cannot be undone.
        </p>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-ghost">Cancel</button>
            </form>
            <form action="{{ route('leave.cancel', $leaveRequest->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error">
                    Yes, Cancel Request
                </button>
            </form>
        </div>
    </div>
</dialog>
</x-layouts.layout> 