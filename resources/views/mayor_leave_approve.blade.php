<x-layouts.layout>
    <x-slot:title>Final Approval - Leave Request</x-slot:title>
    <x-slot:header>Final Approval - Leave Request</x-slot:header>

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
                        <p class="font-medium text-gray-800 text-lg">{{ $leaveTypes[$leaveRequest->leave_type] ?? $leaveRequest->leave_type }}</p>
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

            <!-- Department Recommendation -->
            <div class="p-4 bg-white rounded-lg border border-blue-200 shadow-sm mb-6">
                <h4 class="font-semibold text-blue-600 mb-3">Department Admin Recommendation</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Authorized Personnel</label>
                        <p class="font-medium text-gray-800">{{ $departmentRecommendation->name }}</p>
                        <p class="text-sm text-gray-500">{{ $departmentRecommendation->position }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Decision</label>
                        <p class="font-medium text-gray-800 text-capitalize">{{ $departmentRecommendation->decision }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $departmentRecommendation->reason }}</p>
                    </div>
                </div>
            </div>

            <!-- HR Approval -->
            <div class="p-4 bg-white rounded-lg border border-green-200 shadow-sm mb-6">
                <h4 class="font-semibold text-green-600 mb-3">HR Manager Approval</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">HR Personnel</label>
                        <p class="font-medium text-gray-800">{{ $hrApproval->name }}</p>
                        <p class="text-sm text-gray-500">{{ $hrApproval->position }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Decision</label>
                        <p class="font-medium text-gray-800 text-capitalize">{{ $hrApproval->decision }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $hrApproval->comments }}</p>
                    </div>
                </div>
            </div>

            <!-- Final Approval Action (UI only, no form) -->
            <div class="flex justify-end mt-6">
                <button class="btn btn-success">
                    <i class="fi-rr-check mr-2"></i>
                    Approve
                </button>
                <button class="btn btn-error ml-2">
                    <i class="fi-rr-cross mr-2"></i>
                    Reject
                </button>
            </div>
        </div>
    </div>
</x-layouts.layout> 