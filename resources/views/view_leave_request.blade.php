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
                                JD
                            </span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">Juan Dela Cruz</h4>
                        <p class="text-gray-600">IT Department • IT Specialist</p>
                    </div>
                </div>

                <!-- Request Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Type of Leave</h5>
                        <p class="font-medium text-gray-800 text-lg">Vacation Leave</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Applied On</h5>
                        <p class="font-medium text-gray-800 text-lg">May 25, 2025</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Inclusive Dates</h5>
                        <p class="font-medium text-gray-800 text-lg">May 30, 2025 - June 1, 2025</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Number of Working Days</h5>
                        <p class="font-medium text-gray-800 text-lg">3 days</p>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Where Leave Will Be Spent</h5>
                        <p class="font-medium text-gray-800">Within the Philippines</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                        <h5 class="font-semibold text-blue-600 mb-3">Commutation</h5>
                        <p class="font-medium text-gray-800">Requested</p>
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
                    @else
                        <span class="badge">—</span>
                    @endif
                </div>

                <!-- Cancel Button (UI only) -->
                <div class="flex justify-end">
                    <button class="btn btn-error">
                        <i class="fi-rr-cross mr-2"></i>
                        Cancel Leave Request
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.layout> 