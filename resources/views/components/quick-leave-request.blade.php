<x-dashboard-container title="Quick Leave Request" icon="fi-rr-calendar-plus">
    <form class="flex-grow flex flex-col" id="quickLeaveForm" method="POST" action="{{ route('leave.store') }}">
        @csrf
        
        <!-- Step Indicator -->
        <div class="w-full py-2 mb-3">
            <ul class="steps steps-horizontal w-full steps-sm">
                <li class="step" id="quickStepIndicator1">Type</li>
                <li class="step" id="quickStepIndicator2">Dates</li>
                <li class="step" id="quickStepIndicator3">Details</li>
            </ul>
        </div>
        
        <!-- Step 1: Leave Type -->
        <div id="quickStep1" class="space-y-3">
            <!-- Error message container -->
            <div id="quickStep1Error" class="alert alert-error hidden">
                <i class="fi-rr-exclamation"></i>
                <span id="quickStep1ErrorText">Please select a leave type</span>
            </div>
            <div class="flex flex-col space-y-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="leaveType" value="vacation" class="radio radio-sm radio-primary" onchange="showQuickLeaveSubtype('vacation')">
                    <span>Vacation Leave</span>
                </label>
                
                <!-- Vacation Subtypes (hidden by default) -->
                <div id="quickVacationSubtypes" class="pl-6 hidden space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="vacationSubtype" value="employment" class="radio radio-xs radio-primary">
                        <span class="text-sm">To seek employment</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="vacationSubtype" value="other" class="radio radio-xs radio-primary" onchange="toggleQuickOtherSpecify('vacation')">
                        <span class="text-sm">Other purpose (specify)</span>
                    </label>
                    <div id="quickVacationOtherContainer" class="hidden">
                        <input type="text" name="vacationOtherSpecify" class="input input-bordered input-sm w-full" placeholder="Please specify">
                    </div>
                </div>
                
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="leaveType" value="sick" class="radio radio-sm radio-primary" onchange="showQuickLeaveSubtype('sick')">
                    <span>Sick Leave</span>
                </label>
                
                <!-- Sick Leave Subtypes (hidden by default) -->
                <div id="quickSickSubtypes" class="pl-6 hidden space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="sickSubtype" value="hospital" class="radio radio-xs radio-primary">
                        <span class="text-sm">In Hospital</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="sickSubtype" value="outpatient" class="radio radio-xs radio-primary" onchange="toggleQuickOtherSpecify('sick')">
                        <span class="text-sm">Outpatient (specify)</span>
                    </label>
                    <div id="quickSickOtherContainer" class="hidden">
                        <input type="text" name="sickOtherSpecify" class="input input-bordered input-sm w-full" placeholder="Please specify">
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-3">
                <button type="button" class="btn btn-sm btn-primary" onclick="quickNextStep(1)">
                    Next
                    <i class="fi-rr-arrow-right ml-1"></i>
                </button>
            </div>
        </div>
        
        <!-- Step 2: Date Selection -->
        <div id="quickStep2" class="space-y-3 hidden">
            <!-- Error message container -->
            <div id="quickStep2Error" class="alert alert-error hidden">
                <i class="fi-rr-exclamation"></i>
                <span id="quickStep2ErrorText">Please select valid dates</span>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label py-1">
                        <span class="label-text font-medium">Start Date</span>
                    </label>
                    <input type="date" name="startDate" class="input input-bordered input-sm w-full" required onchange="calculateDays()">
                </div>
                
                <div class="form-control">
                    <label class="label py-1">
                        <span class="label-text font-medium">End Date</span>
                    </label>
                    <input type="date" name="endDate" class="input input-bordered input-sm w-full" required onchange="calculateDays()">
                </div>
            </div>
            
            <div class="form-control">
                <label class="label py-1">
                    <span class="label-text font-medium">Number of Days</span>
                </label>
                <input type="text" name="numberOfDays" id="numberOfDays" class="input input-bordered input-sm w-full" readonly>
            </div>
            
            <div class="flex justify-between mt-3">
                <button type="button" class="btn btn-sm btn-outline" onclick="quickPrevStep(2)">
                    <i class="fi-rr-arrow-left mr-1"></i>
                    Back
                </button>
                <button type="button" class="btn btn-sm btn-primary" onclick="quickNextStep(2)">
                    Next
                    <i class="fi-rr-arrow-right ml-1"></i>
                </button>
            </div>
        </div>
        
        <!-- Step 3: Additional Details -->
        <div id="quickStep3" class="space-y-3 hidden">
            <!-- Error message container -->
            <div id="quickStep3Error" class="alert alert-error hidden">
                <i class="fi-rr-exclamation"></i>
                <span id="quickStep3ErrorText">Please complete all required fields</span>
            </div>
            <!-- Where to spend leave -->
            <div class="form-control">
                <label class="label py-1">
                    <span class="label-text font-medium">Where will leave be spent?</span>
                </label>
                <div class="flex flex-col space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="locationType" value="philippines" class="radio radio-sm radio-primary">
                        <span>Within the Philippines</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="locationType" value="abroad" class="radio radio-sm radio-primary" onchange="toggleLocationSpecify()">
                        <span>Abroad (specify)</span>
                    </label>
                    <div id="locationSpecifyContainer" class="hidden pl-6">
                        <input type="text" name="location_specify" class="input input-bordered input-sm w-full" placeholder="Country">
                    </div>
                </div>
            </div>
            
            <!-- Commutation Request -->
            <div class="form-control">
                <label class="label cursor-pointer justify-start gap-2 py-1">
                    <input type="checkbox" name="commutation" value="1" class="checkbox checkbox-sm checkbox-primary">
                    <span class="label-text">Request for commutation of leave credits</span>
                </label>
            </div>
            
            <div class="flex justify-between mt-3">
                <button type="button" class="btn btn-sm btn-outline" onclick="quickPrevStep(3)">
                    <i class="fi-rr-arrow-left mr-1"></i>
                    Back
                </button>
                <button type="button" class="btn btn-sm btn-primary" onclick="showQuickConfirmModal()">
                    Submit
                    <i class="fi-rr-paper-plane ml-1"></i>
                </button>
            </div>
        </div>
        
        <input type="hidden" id="formStep" value="quick">
    </form>
    
    <!-- Confirmation Modal -->
    <dialog id="quickConfirmModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirm Leave Request</h3>
            <p class="py-4">Are you sure you want to submit this leave request?</p>
            <div class="modal-action">
                <button id="quickCancelSubmit" class="btn btn-outline">Cancel</button>
                <button id="quickConfirmSubmit" class="btn btn-primary">Submit</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
    
    <!-- Success Modal -->
    <dialog id="quickSuccessModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-success">Success!</h3>
            <p id="quickSuccessMessage" class="py-4">Your leave request has been submitted successfully and is pending approval.</p>
            <div class="modal-action">
                <button onclick="document.getElementById('quickSuccessModal').close()" class="btn btn-primary">OK</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
    
    <script>
        // Helper function to show error message
        function showErrorMessage(stepNumber, message) {
            const errorContainer = document.getElementById(`quickStep${stepNumber}Error`);
            const errorText = document.getElementById(`quickStep${stepNumber}ErrorText`);
            
            errorText.textContent = message;
            errorContainer.classList.remove('hidden');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                errorContainer.classList.add('hidden');
            }, 5000);
        }
        
        // Helper function to hide error message
        function hideErrorMessage(stepNumber) {
            const errorContainer = document.getElementById(`quickStep${stepNumber}Error`);
            errorContainer.classList.add('hidden');
        }
        
        // Step navigation functions
        function quickNextStep(currentStep) {
            // Hide any previous error message
            hideErrorMessage(currentStep);
            
            // Validate current step
            if (currentStep === 1) {
                const leaveType = document.querySelector('input[name="leaveType"]:checked')?.value;
                if (!leaveType) {
                    showErrorMessage(1, 'Please select a leave type');
                    return;
                }
                
                if (leaveType === 'vacation') {
                    const vacationSubtype = document.querySelector('input[name="vacationSubtype"]:checked')?.value;
                    if (!vacationSubtype) {
                        showErrorMessage(1, 'Please select a vacation leave subtype');
                        return;
                    }
                    
                    if (vacationSubtype === 'other') {
                        const otherSpecify = document.querySelector('input[name="vacationOtherSpecify"]').value;
                        if (!otherSpecify.trim()) {
                            showErrorMessage(1, 'Please specify the other purpose');
                            return;
                        }
                    }
                } else if (leaveType === 'sick') {
                    const sickSubtype = document.querySelector('input[name="sickSubtype"]:checked')?.value;
                    if (!sickSubtype) {
                        showErrorMessage(1, 'Please select a sick leave subtype');
                        return;
                    }
                    
                    if (sickSubtype === 'outpatient') {
                        const otherSpecify = document.querySelector('input[name="sickOtherSpecify"]').value;
                        if (!otherSpecify.trim()) {
                            showErrorMessage(1, 'Please specify the outpatient details');
                            return;
                        }
                    }
                }
            } else if (currentStep === 2) {
                const startDate = document.querySelector('input[name="startDate"]').value;
                const endDate = document.querySelector('input[name="endDate"]').value;
                
                if (!startDate || !endDate) {
                    showErrorMessage(2, 'Please select both start and end dates');
                    return;
                }
                
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                if (end < start) {
                    showErrorMessage(2, 'End date cannot be earlier than start date');
                    return;
                }
            }
            
            // Hide current step, show next step
            document.getElementById(`quickStep${currentStep}`).classList.add('hidden');
            document.getElementById(`quickStep${currentStep + 1}`).classList.remove('hidden');
            
            // Update step indicator
            document.getElementById(`quickStepIndicator${currentStep}`).classList.add('step-primary');
            document.getElementById(`quickStepIndicator${currentStep + 1}`).classList.add('step-primary');
        }
        
        function quickPrevStep(currentStep) {
            // Hide current step, show previous step
            document.getElementById(`quickStep${currentStep}`).classList.add('hidden');
            document.getElementById(`quickStep${currentStep - 1}`).classList.remove('hidden');
            
            // Update step indicator
            document.getElementById(`quickStepIndicator${currentStep}`).classList.remove('step-primary');
        }
        
        function showQuickLeaveSubtype(type) {
            // Hide all subtypes first
            document.getElementById('quickVacationSubtypes').classList.add('hidden');
            document.getElementById('quickSickSubtypes').classList.add('hidden');
            
            // Show the selected subtype
            if (type === 'vacation') {
                document.getElementById('quickVacationSubtypes').classList.remove('hidden');
            } else if (type === 'sick') {
                document.getElementById('quickSickSubtypes').classList.remove('hidden');
            }
        }
        
        function toggleQuickOtherSpecify(type) {
            if (type === 'vacation') {
                const isOther = document.querySelector('input[name="vacationSubtype"]:checked')?.value === 'other';
                document.getElementById('quickVacationOtherContainer').classList.toggle('hidden', !isOther);
            } else if (type === 'sick') {
                const isOutpatient = document.querySelector('input[name="sickSubtype"]:checked')?.value === 'outpatient';
                document.getElementById('quickSickOtherContainer').classList.toggle('hidden', !isOutpatient);
            }
        }
        
        function toggleLocationSpecify() {
            const isAbroad = document.querySelector('input[name="locationType"]:checked')?.value === 'abroad';
            document.getElementById('locationSpecifyContainer').classList.toggle('hidden', !isAbroad);
        }
        
        function calculateDays() {
            const startDate = document.querySelector('input[name="startDate"]').value;
            const endDate = document.querySelector('input[name="endDate"]').value;
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                // Calculate the difference in days
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 to include both start and end dates
                
                document.getElementById('numberOfDays').value = diffDays;
            }
        }
        
        // Show confirmation modal
        function showQuickConfirmModal() {
            // Hide any previous error message
            hideErrorMessage(3);
            
            // Validate the form first
            const locationType = document.querySelector('input[name="locationType"]:checked')?.value;
            if (!locationType) {
                showErrorMessage(3, 'Please select where the leave will be spent');
                return;
            }
            
            if (locationType === 'abroad') {
                const locationSpecify = document.querySelector('input[name="location_specify"]').value;
                if (!locationSpecify.trim()) {
                    showErrorMessage(3, 'Please specify the country');
                    return;
                }
            }
            
            // Show the confirmation modal
            const confirmModal = document.getElementById('quickConfirmModal');
            confirmModal.showModal();
        }
        
        // Hide confirmation modal
        function hideQuickConfirmModal() {
            const confirmModal = document.getElementById('quickConfirmModal');
            confirmModal.close();
        }
        
        // Submit the leave request
        function submitQuickLeaveRequest() {
            // Hide the confirmation modal
            hideQuickConfirmModal();
            
            // Submit the form
            document.getElementById('quickLeaveForm').submit();
        }
        
        // Initialize date fields and set up event handlers
        document.addEventListener('DOMContentLoaded', function() {
            // Set first step indicator as active
            document.getElementById('quickStepIndicator1').classList.add('step-primary');
            
            const today = new Date().toISOString().split('T')[0];
            const startDateInput = document.querySelector('input[name="startDate"]');
            const endDateInput = document.querySelector('input[name="endDate"]');
            
            if (startDateInput && !startDateInput.value) {
                startDateInput.value = today;
            }
            
            if (endDateInput && !endDateInput.value) {
                endDateInput.value = today;
            }
            
            // Calculate initial number of days
            calculateDays();
            
            // Set default location type
            const philippinesRadio = document.querySelector('input[name="locationType"][value="philippines"]');
            if (philippinesRadio) {
                philippinesRadio.checked = true;
            }
            
            // Set up event handlers for the confirmation modal buttons
            const cancelSubmit = document.getElementById('quickCancelSubmit');
            const confirmSubmit = document.getElementById('quickConfirmSubmit');
            
            if (cancelSubmit) {
                cancelSubmit.addEventListener('click', hideQuickConfirmModal);
            }
            
            if (confirmSubmit) {
                confirmSubmit.addEventListener('click', submitQuickLeaveRequest);
            }
            
            // Set up modal event listeners
            const confirmModal = document.getElementById('quickConfirmModal');
            const successModal = document.getElementById('quickSuccessModal');
            
            // The modal-backdrop form with method="dialog" will automatically
            // close the modal when clicked, so we don't need additional click handlers
            
            // Check for success message in session
            @if(session('success'))
                console.log('Success message found in session, showing success modal');
                const successMessage = document.getElementById('quickSuccessMessage');
                successMessage.textContent = "{{ session('success') }}";
                successModal.showModal();
                
                // Automatically close the success modal after 5 seconds
                setTimeout(function() {
                    successModal.close();
                }, 5000); // 5000 milliseconds = 5 seconds
            @endif
        });
    </script>
</x-dashboard-container>