<x-layouts.layout>
    <x-slot:title>Department Leave Recommendation</x-slot:title>
    <x-slot:header>Department Leave Recommendation</x-slot:header>

    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                <i class="fi-rr-check-circle text-green-500 mr-2"></i>
                Department Leave Recommendation Process
            </h2>

            <!-- Step Indicator -->
            <div class="w-full py-4">
                <ul class="steps steps-horizontal w-full">
                    <li class="step step-primary">Review Request</li>
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
                                <div
                                    class="w-14 h-14 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">DP</span>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-800">Daniel Pombo</h4>
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
                                <p class="font-medium text-gray-800 text-lg">May 15, 2023</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                                <h5 class="font-semibold text-blue-600 mb-3">Inclusive Dates</h5>
                                <p class="font-medium text-gray-800 text-lg">Jun 1-5, 2023</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                                <h5 class="font-semibold text-blue-600 mb-3">Number of Days</h5>
                                <p class="font-medium text-gray-800 text-lg">5 days</p>
                            </div>
                        </div>

                        <!-- Reason and Work Coverage -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                                <h5 class="font-semibold text-blue-600 mb-3">Reason for Leave</h5>
                                <p class="text-gray-700">
                                    I am planning a family vacation to visit relatives. This has been planned for several months and all arrangements have been made.
                                </p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                                <h5 class="font-semibold text-blue-600 mb-3">Work Coverage Plan</h5>
                                <p class="text-gray-700 mb-3">
                                    <span class="font-medium">Work Coverage:</span> 
                                    <span>Jane Smith will handle my ongoing projects during my absence.</span>
                                </p>
                                <p class="text-gray-700">
                                    <span class="font-medium">Critical Tasks:</span> 
                                    <span>The quarterly report will be completed before my departure. All client meetings have been rescheduled.</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" class="btn bg-blue-500 hover:bg-blue-600 text-white"
                            onclick="nextStep(1)">
                            Next
                            <i class="fi-rr-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Recommendation Decision -->
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
                        <button type="button" class="btn bg-blue-500 hover:bg-blue-600 text-white"
                            onclick="validateStep2AndProceed()">
                            Next
                            <i class="fi-rr-arrow-right ml-2"></i>
                        </button>
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
                        <button type="submit" class="btn bg-green-500 hover:bg-green-600 text-white">
                            <i class="fi-rr-check mr-2"></i>
                            Submit Recommendation
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function nextStep(currentStep) {
            // Hide current step
            document.getElementById('step' + currentStep).classList.add('hidden');
            
            // Show next step
            document.getElementById('step' + (currentStep + 1)).classList.remove('hidden');
            
            // Update step indicator
            document.getElementById('step' + (currentStep + 1) + 'Indicator').classList.add('step-primary');
            
            // If moving to Step 3, update the summary
            if (currentStep === 2) {
                updateSummary();
            }
        }
        
        function prevStep(currentStep) {
            // Hide current step
            document.getElementById('step' + currentStep).classList.add('hidden');
            
            // Show previous step
            document.getElementById('step' + (currentStep - 1)).classList.remove('hidden');
            
            // Update step indicator
            document.getElementById('step' + currentStep + 'Indicator').classList.remove('step-primary');
        }
        
        function validateStep2AndProceed() {
            const recommendation = document.querySelector('input[name="recommendation"]:checked').value;
            
            if (recommendation === 'disapprove') {
                const disapprovalReason = document.querySelector('textarea[name="disapproval_reason"]').value.trim();
                
                if (!disapprovalReason) {
                    alert('Please provide a reason for disapproval.');
                    return;
                }
            }
            
            nextStep(2);
        }
        
        function toggleReasonInput() {
            const recommendation = document.querySelector('input[name="recommendation"]:checked').value;
            const approvalReasonContainer = document.getElementById('approvalReasonContainer');
            const disapprovalReasonContainer = document.getElementById('disapprovalReasonContainer');
            
            if (recommendation === 'approve') {
                approvalReasonContainer.classList.remove('hidden');
                disapprovalReasonContainer.classList.add('hidden');
            } else {
                approvalReasonContainer.classList.add('hidden');
                disapprovalReasonContainer.classList.remove('hidden');
            }
        }
        
        function updateSummary() {
            const recommendation = document.querySelector('input[name="recommendation"]:checked').value;
            document.getElementById('summaryRecommendation').textContent = 
                recommendation === 'approve' ? 'Approve' : 'Disapprove';
            
            // Update reason
            let reasonText = '';
            if (recommendation === 'approve') {
                reasonText = document.querySelector('textarea[name="approval_reason"]').value;
            } else {
                reasonText = document.querySelector('textarea[name="disapproval_reason"]').value;
            }
            
            document.getElementById('summaryReason').textContent = reasonText || 'No reason provided.';
        }
        
        // Initialize the form when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Set up initial state
            toggleReasonInput();
        });
    </script>
</x-layouts.layout>


