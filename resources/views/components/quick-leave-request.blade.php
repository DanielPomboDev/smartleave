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
                    <div class="alert alert-info alert-sm mb-2">
                        <i class="fi-rr-info"></i>
                        <span class="text-xs">Vacation leave must be applied at least 5 days before the start date</span>
                    </div>
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
                    <div class="alert alert-success alert-sm mb-2">
                        <i class="fi-rr-check"></i>
                        <span class="text-xs">Sick leave can be applied after the leave period</span>
                    </div>
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
                <button type="button" class="btn btn-sm btn-primary inline-flex items-center" onclick="quickNextStep(1)">
                    Next
                    <i class="fi-rr-arrow-right"></i>
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
                        <span class="label-text-alt text-warning" id="startDateWarning"></span>
                    </label>
                    <input type="date" name="startDate" id="startDate" class="input input-bordered input-sm w-full" required onchange="validateDates()">
                </div>
                
                <div class="form-control">
                    <label class="label py-1">
                        <span class="label-text font-medium">End Date</span>
                    </label>
                    <input type="date" name="endDate" id="endDate" class="input input-bordered input-sm w-full" required onchange="validateDates()">
                </div>
            </div>
            
            <!-- Date validation message -->
            <div id="dateValidationMessage" class="alert alert-warning hidden">
                <i class="fi-rr-exclamation"></i>
                <span id="dateValidationText"></span>
            </div>
            
            <div class="form-control">
                <label class="label py-1">
                    <span class="label-text font-medium">Number of Days</span>
                </label>
                <input type="text" name="numberOfDays" id="numberOfDays" class="input input-bordered input-sm w-full" readonly>
            </div>
            
            <div class="flex justify-between mt-3">
                <button type="button" class="btn btn-sm btn-outline inline-flex items-center" onclick="quickPrevStep(2)">
                    <i class="fi-rr-arrow-left mr-1"></i>
                    Back
                </button>
                <button type="button" class="btn btn-sm btn-primary inline-flex items-center" onclick="quickNextStep(2)">
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
                <button type="button" class="btn btn-sm btn-outline inline-flex items-center" onclick="quickPrevStep(3)">
                    <i class="fi-rr-arrow-left mr-1"></i>
                    Back
                </button>
                <button type="button" class="btn btn-sm btn-primary inline-flex items-center" onclick="showQuickConfirmModal()">
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
                // Use the comprehensive date validation function
                if (!validateDates()) {
                    return; // validateDates() will show the appropriate error message
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
            
            // Update date restrictions based on leave type
            updateDateRestrictions(type);
            
            // Clear any existing date validation messages
            const validationMessage = document.getElementById('dateValidationMessage');
            if (validationMessage) {
                validationMessage.classList.add('hidden');
            }
        }
        
        function updateDateRestrictions(leaveType) {
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            const today = new Date().toISOString().split('T')[0];
            
            if (leaveType === 'vacation') {
                // For vacation leave, set minimum start date to 5 days from today
                const minVacationDate = new Date();
                minVacationDate.setDate(minVacationDate.getDate() + 5);
                const minVacationDateStr = minVacationDate.toISOString().split('T')[0];
                
                if (startDateInput) {
                    startDateInput.min = minVacationDateStr;
                    // If current start date is less than minimum, update it
                    if (startDateInput.value && startDateInput.value < minVacationDateStr) {
                        startDateInput.value = minVacationDateStr;
                        // Also update end date if it's before the new start date
                        if (endDateInput && endDateInput.value < minVacationDateStr) {
                            endDateInput.value = minVacationDateStr;
                        }
                    }
                }
            } else if (leaveType === 'sick') {
                // For sick leave, allow dates from today onwards
                if (startDateInput) {
                    startDateInput.min = today;
                }
            }
            
            // Re-validate dates after changing restrictions
            if (startDateInput && endDateInput) {
                validateDates();
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
        
        // Function to validate dates based on leave type
        function validateDates() {
            const startDateInput = document.querySelector('input[name="startDate"]');
            const endDateInput = document.querySelector('input[name="endDate"]');
            const leaveType = document.querySelector('input[name="leaveType"]:checked')?.value;
            
            // Clear previous validation messages
            const validationMessage = document.getElementById('dateValidationMessage');
            const validationText = document.getElementById('dateValidationText');
            const startDateWarning = document.getElementById('startDateWarning');
            
            if (validationMessage) {
                validationMessage.classList.add('hidden');
            }
            
            if (startDateWarning) {
                startDateWarning.textContent = '';
            }
            
            if (!startDateInput || !endDateInput) return true;
            
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            
            if (!startDate || !endDate) return true;
            
            const start = new Date(startDate);
            const end = new Date(endDate);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Reset time to start of day
            
            // Check if end date is before start date
            if (end < start) {
                if (validationText) {
                    validationText.textContent = 'End date cannot be earlier than start date';
                }
                if (validationMessage) {
                    validationMessage.classList.remove('hidden');
                }
                return false;
            }
            
            // Check if start date is in the past
            if (start < today) {
                if (validationText) {
                    validationText.textContent = 'Start date cannot be in the past';
                }
                if (validationMessage) {
                    validationMessage.classList.remove('hidden');
                }
                return false;
            }
            
            // Vacation leave specific validation (5 days advance notice)
            if (leaveType === 'vacation') {
                const daysDifference = Math.ceil((start - today) / (1000 * 60 * 60 * 24));
                
                if (daysDifference < 5) {
                    if (validationText) {
                        validationText.textContent = 'Vacation leave must be applied at least 5 days before the start date';
                    }
                    if (validationMessage) {
                        validationMessage.classList.remove('hidden');
                    }
                    if (startDateWarning) {
                        startDateWarning.textContent = `(${daysDifference} days notice - requires 5 days minimum)`;
                    }
                    return false;
                } else {
                    if (startDateWarning) {
                        startDateWarning.textContent = `(${daysDifference} days notice - OK)`;
                    }
                }
            }
            
            // Sick leave can be applied after the leave period
            if (leaveType === 'sick') {
                // Allow sick leave to be applied even after the leave period
                // No warning text needed
            }
            
            // If all validations pass, calculate days
            calculateDays();
            
            // Validate leave credits (but don't block submission)
            validateLeaveCredits(leaveType);
            
            return true;
        }
        
        function validateLeaveCredits(leaveType) {
            const numberOfDays = parseInt(document.getElementById('numberOfDays').value) || 0;
            
            if (!leaveType || numberOfDays <= 0) return true; // Skip validation if not ready
            
            // Get available credits from the dashboard (these would be passed from the backend)
            let availableCredits = 0;
            if (leaveType === 'vacation') {
                // Get vacation balance from the dashboard
                const vacationCard = document.querySelector('[data-leave-type="vacation"]');
                if (vacationCard) {
                    const balanceText = vacationCard.querySelector('.text-4xl')?.textContent || '0';
                    availableCredits = parseFloat(balanceText) || 0;
                } else {
                    // Fallback to default values if dashboard elements not found
                    availableCredits = 15; // Default vacation balance
                }
            } else if (leaveType === 'sick') {
                // Get sick balance from the dashboard
                const sickCard = document.querySelector('[data-leave-type="sick"]');
                if (sickCard) {
                    const balanceText = sickCard.querySelector('.text-4xl')?.textContent || '0';
                    availableCredits = parseFloat(balanceText) || 0;
                } else {
                    // Fallback to default values if dashboard elements not found
                    availableCredits = 12; // Default sick balance
                }
            }
            
            // Check if user has sufficient credits
            if (numberOfDays > availableCredits) {
                const validationMessage = document.getElementById('dateValidationMessage');
                const validationText = document.getElementById('dateValidationText');
                validationText.textContent = `Insufficient ${leaveType} leave credits. You have ${availableCredits} days available but are requesting ${numberOfDays} days. This leave will be considered without pay.`;
                validationMessage.classList.remove('hidden');
                // Return true to allow submission to continue
                return true;
            } else {
                // Hide validation message if credits are sufficient
                const validationMessage = document.getElementById('dateValidationMessage');
                if (validationMessage && !validationMessage.classList.contains('hidden')) {
                    validationMessage.classList.add('hidden');
                }
            }
            
            return true;
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
            // Final validation before submission
            if (!validateDates()) {
                hideQuickConfirmModal();
                return;
            }
            
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
            
            // Set min dates to prevent selecting past dates
            if (startDateInput) {
                startDateInput.min = today;
                if (!startDateInput.value) {
                    startDateInput.value = today;
                }
            }
            
            if (endDateInput) {
                endDateInput.min = today;
                if (!endDateInput.value) {
                    endDateInput.value = today;
                }
            }
            
            // Set initial date restrictions based on default leave type (if any is selected)
            const selectedLeaveType = document.querySelector('input[name="leaveType"]:checked')?.value;
            if (selectedLeaveType) {
                updateDateRestrictions(selectedLeaveType);
            }
            
            // Calculate initial number of days
            calculateDays();
            
            // Set default location type
            const philippinesRadio = document.querySelector('input[name="locationType"][value="philippines"]');
            if (philippinesRadio) {
                philippinesRadio.checked = true;
            }
            
            // Add event listener to update end date min when start date changes
            if (startDateInput) {
                startDateInput.addEventListener('change', function() {
                    if (endDateInput && this.value) {
                        endDateInput.min = this.value;
                        if (endDateInput.value && endDateInput.value < this.value) {
                            endDateInput.value = this.value;
                        }
                        validateDates();
                    }
                });
            }
            
            // Add event listener to update date restrictions when leave type changes
            const leaveTypeInputs = document.querySelectorAll('input[name="leaveType"]');
            leaveTypeInputs.forEach(input => {
                input.addEventListener('change', function() {
                    updateDateRestrictions(this.value);
                });
            });
            
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