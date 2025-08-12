@php
use App\Models\LeaveRequest;
@endphp

<x-layouts.layout>
    <x-slot:title>Department Leave Recommendation</x-slot:title>
    <x-slot:header>Department Leave Recommendation</x-slot:header>

    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success mb-6">
                    <div class="flex items-center">
                        <i class="fi-rr-check-circle text-xl mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error mb-6">
                    <div class="flex items-center">
                        <i class="fi-rr-cross-circle text-xl mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                <i class="fi-rr-check-circle text-green-500 mr-2"></i>
                Department Leave Recommendation Process
            </h2>

            @if($leaveRequest->status !== LeaveRequest::STATUS_PENDING)
                <div class="alert alert-info mb-6">
                    <div class="flex items-center">
                        <i class="fi-rr-info text-xl mr-2"></i>
                        <span>
                            This request is already {{ $leaveRequest->status === LeaveRequest::STATUS_RECOMMENDED ? 'recommended' : ($leaveRequest->isHrApproved() ? 'HR approved' : ($leaveRequest->isApproved() ? 'approved' : ($leaveRequest->isDisapproved() ? 'disapproved' : 'processed'))) }}. You can view details but cannot submit another recommendation.
                        </span>
                    </div>
                </div>
            @endif

            <!-- Step Indicator -->
            <div class="w-full py-4">
                <ul class="steps steps-horizontal w-full">
                    <li class="step step-primary" id="step1Indicator">Review Request</li>
                    <li class="step" id="step2Indicator">Recommendation Decision</li>
                    <li class="step" id="step3Indicator">Confirmation</li>
                </ul>
            </div>

            <form id="approvalForm" method="POST" action="{{ route('department.leave.approve.process', ['id' => $leaveId]) }}">
                @csrf

                <!-- Step 1: Review Request -->
                <div id="step1" class="space-y-6">
                    <h3 class="font-medium text-lg text-gray-800">Review Leave Request</h3>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 shadow-sm">
                        <!-- Employee Info -->
                        <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                            <div class="avatar mr-4">
                                <div class="w-14 h-14 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg flex items-center justify-center w-full h-full">{{ strtoupper(substr($leaveRequest->user->first_name, 0, 1) . substr($leaveRequest->user->last_name, 0, 1)) }}</span>
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
                                <p class="font-medium text-gray-800 text-lg">{{ LeaveRequest::LEAVE_TYPES[$leaveRequest->leave_type] ?? $leaveRequest->leave_type }}</p>
                                @if($leaveRequest->subtype)
                                    <p class="text-gray-600 mt-1">{{ $leaveRequest->subtype }}</p>
                                @endif
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                                <h5 class="font-semibold text-blue-600 mb-3">Applied On</h5>
                                <p class="font-medium text-gray-800 text-lg">{{ $leaveRequest->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                                <h5 class="font-semibold text-blue-600 mb-3">Inclusive Dates</h5>
                                <p class="font-medium text-gray-800 text-lg">
                                    @if($leaveRequest->start_date->isSameDay($leaveRequest->end_date))
                                        {{ $leaveRequest->start_date->format('M d, Y') }}
                                    @else
                                        {{ $leaveRequest->start_date->format('M d') }}-{{ $leaveRequest->end_date->format('d, Y') }}
                                    @endif
                                </p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                                <h5 class="font-semibold text-blue-600 mb-3">Number of Days</h5>
                                <p class="font-medium text-gray-800 text-lg">{{ $leaveRequest->number_of_days }} days</p>
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                    <div class="flex justify-end mt-6">
                        @if($leaveRequest->status === LeaveRequest::STATUS_PENDING)
                            <button type="button" class="btn bg-blue-500 hover:bg-blue-600 text-white" onclick="nextStep(1)">
                                Next
                                <i class="fi-rr-arrow-right ml-2"></i>
                            </button>
                        @else
                            <button type="button" class="btn" disabled>
                                Next
                                <i class="fi-rr-lock ml-2"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Step 2: Recommendation -->
                <div id="step2" class="hidden space-y-6">
                    <h3 class="font-medium text-lg text-gray-800">Department Recommendation</h3>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h4 class="font-medium mb-4">Recommendation/Approval</h4>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Decision</span>
                            </label>
                            <div class="flex flex-col space-y-3">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="recommendation" value="approve"
                                        class="radio radio-success" checked onchange="toggleReasonInput()">
                                    <span>Approve</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="recommendation" value="disapprove"
                                        class="radio radio-error" onchange="toggleReasonInput()">
                                    <span>Disapprove</span>
                                </label>
                            </div>
                        </div>

                        <div id="approvalReasonContainer" class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Reason for Approval (Optional)</span>
                            </label>
                            <textarea name="approval_reason" class="textarea textarea-bordered h-24"
                                placeholder="Enter reason for approval (optional)"></textarea>
                        </div>

                        <div id="disapprovalReasonContainer" class="form-control mb-4 hidden">
                            <label class="label">
                                <span class="label-text font-medium">Reason for Disapproval <span class="text-red-500">*</span></span>
                            </label>
                            <textarea name="disapproval_reason" class="textarea textarea-bordered h-24"
                                placeholder="Enter reason for disapproval"></textarea>
                            <label class="label">
                                <span class="label-text-alt text-red-500">Required if disapproving the leave request</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button"
                            class="btn btn-outline border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white"
                            onclick="prevStep(2)">
                            <i class="fi-rr-arrow-left mr-2"></i>
                            Previous
                        </button>
                        @if($leaveRequest->status === LeaveRequest::STATUS_PENDING)
                            <button type="button" class="btn bg-blue-500 hover:bg-blue-600 text-white"
                                onclick="validateStep2AndProceed()">
                                Next
                                <i class="fi-rr-arrow-right ml-2"></i>
                            </button>
                        @else
                            <button type="button" class="btn" disabled>
                                Next
                                <i class="fi-rr-lock ml-2"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Step 3: Confirmation -->
                <div id="step3" class="hidden space-y-6">
                    <h3 class="font-medium text-lg text-gray-800">Confirm Your Recommendation</h3>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 shadow-sm">
                        <div class="alert alert-info mb-4">
                            <i class="fi-rr-info mr-2"></i>
                            <span>Please review your recommendation before submitting. This will be forwarded to HR for final approval.</span>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h5 class="font-bold text-gray-700">Your Recommendation</h5>
                                <p class="text-gray-800 font-medium" id="summaryRecommendation">Approve</p>
                            </div>

                            <div>
                                <h5 class="font-bold text-gray-700">Reason</h5>
                                <p class="text-gray-800" id="summaryReason"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button"
                            class="btn btn-outline border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white"
                            onclick="prevStep(3)">
                            <i class="fi-rr-arrow-left mr-2"></i>
                            Previous
                        </button>
                        @if($leaveRequest->status === LeaveRequest::STATUS_PENDING)
                            <button type="submit" class="btn bg-green-500 hover:bg-green-600 text-white">
                                <i class="fi-rr-check mr-2"></i>
                                Submit Recommendation
                            </button>
                        @else
                            <button type="button" class="btn" disabled>
                                <i class="fi-rr-lock mr-2"></i>
                                Submit Recommendation
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentStep = 1;

        function showStep(step) {
            // Hide all steps
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step3').classList.add('hidden');
            
            // Show current step
            document.getElementById('step' + step).classList.remove('hidden');

            // Update step indicators
            document.getElementById('step1Indicator').classList.remove('step-primary');
            document.getElementById('step2Indicator').classList.remove('step-primary');
            document.getElementById('step3Indicator').classList.remove('step-primary');
            
            // Set current step as active
            document.getElementById('step' + step + 'Indicator').classList.add('step-primary');
        }

        function nextStep(step) {
            currentStep = step + 1;
            showStep(currentStep);
        }

        function prevStep(step) {
            currentStep = step - 1;
            showStep(currentStep);
        }

        function toggleReasonInput() {
            const recommendation = document.querySelector('input[name="recommendation"]:checked').value;
            const approvalContainer = document.getElementById('approvalReasonContainer');
            const disapprovalContainer = document.getElementById('disapprovalReasonContainer');

            if (recommendation === 'approve') {
                approvalContainer.classList.remove('hidden');
                disapprovalContainer.classList.add('hidden');
            } else {
                approvalContainer.classList.add('hidden');
                disapprovalContainer.classList.remove('hidden');
            }
        }

        function validateStep2AndProceed() {
            const recommendation = document.querySelector('input[name="recommendation"]:checked').value;
            const disapprovalReason = document.querySelector('textarea[name="disapproval_reason"]').value;

            if (recommendation === 'disapprove' && !disapprovalReason.trim()) {
                alert('Please provide a reason for disapproval');
                return;
            }

            // Update summary
            document.getElementById('summaryRecommendation').textContent = recommendation === 'approve' ? 'Approve' : 'Disapprove';
            document.getElementById('summaryReason').textContent = recommendation === 'approve' 
                ? document.querySelector('textarea[name="approval_reason"]').value || 'No reason provided'
                : disapprovalReason;

            nextStep(2);
        }

        // Add form submission handler
        document.getElementById('approvalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const recommendation = document.querySelector('input[name="recommendation"]:checked').value;
            const disapprovalReason = document.querySelector('textarea[name="disapproval_reason"]').value;

            if (recommendation === 'disapprove' && !disapprovalReason.trim()) {
                alert('Please provide a reason for disapproval');
                return;
            }

            // Submit the form
            this.submit();
        });

        // Initialize
        showStep(1);
    </script>
    @endpush
</x-layouts.layout>


