<x-layouts.layout>
    <x-slot:title>Request Leave</x-slot:title>
    <x-slot:header>Request Leave</x-slot:header>
    
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
                <i class="fi-rr-calendar-plus text-blue-500 mr-2"></i>
                New Leave Request
            </h2>
            
            <!-- Step Indicator -->
            <div class="w-full py-4">
                <ul class="steps steps-horizontal w-full">
                    <li class="step step-primary">Leave Type</li>
                    <li class="step">Date Selection</li>
                    <li class="step">Details</li>
                    <li class="step">Confirmation</li>
                </ul>
            </div>
            
            <form class="space-y-6" id="leaveRequestForm" method="POST" action="{{ route('leave.store') }}">
                @csrf
                <!-- Step 1: Leave Type -->
                <div id="step1" class="space-y-6">
                    <h3 class="font-medium text-lg text-gray-800">Select Leave Type</h3>
                    
                    <div class="space-y-4">
                        <!-- Vacation Leave -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-colors bg-gray-50">
                            <div class="flex items-center cursor-pointer" onclick="selectLeaveType('vacation')">
                                <input type="radio" name="leaveType" id="vacationLeave" value="vacation" class="radio radio-primary" onchange="showSubtype('vacation')">
                                <label for="vacationLeave" class="ml-2 font-medium text-gray-800">Vacation Leave</label>
                            </div>
                            
                            <div id="vacationSubtypes" class="pl-6 mt-3 hidden space-y-3">
                                <div class="form-control">
                                    <label class="label cursor-pointer justify-start gap-2" onclick="event.stopPropagation()">
                                        <input type="radio" name="vacationSubtype" value="employment" class="radio radio-sm radio-primary" onclick="event.stopPropagation()">
                                        <span class="label-text text-gray-700">To seek employment</span>
                                    </label>
                                </div>
                                
                                <div class="form-control">
                                    <label class="label cursor-pointer justify-start gap-2" onclick="event.stopPropagation()">
                                        <input type="radio" name="vacationSubtype" value="other" class="radio radio-sm radio-primary" onchange="toggleOtherSpecify('vacation')" onclick="event.stopPropagation()">
                                        <span class="label-text text-gray-700">Other (please specify)</span>
                                    </label>
                                    
                                    <input type="text" id="vacationOtherSpecify" name="vacationOtherSpecify" placeholder="Please specify" class="input input-bordered input-sm mt-1 ml-6 w-3/4 hidden border-gray-300 focus:border-blue-500" onclick="event.stopPropagation()">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sick Leave -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-colors bg-gray-50">
                            <div class="flex items-center cursor-pointer" onclick="selectLeaveType('sick')">
                                <input type="radio" name="leaveType" id="sickLeave" value="sick" class="radio radio-primary" onchange="showSubtype('sick')">
                                <label for="sickLeave" class="ml-2 font-medium text-gray-800">Sick Leave</label>
                            </div>
                            
                            <div id="sickSubtypes" class="pl-6 mt-3 hidden space-y-3">
                                <div class="form-control">
                                    <label class="label cursor-pointer justify-start gap-2" onclick="event.stopPropagation()">
                                        <input type="radio" name="sickSubtype" value="maternity" class="radio radio-sm radio-primary" onclick="event.stopPropagation()">
                                        <span class="label-text text-gray-700">Maternity</span>
                                    </label>
                                </div>
                                
                                <div class="form-control">
                                    <label class="label cursor-pointer justify-start gap-2" onclick="event.stopPropagation()">
                                        <input type="radio" name="sickSubtype" value="other" class="radio radio-sm radio-primary" onchange="toggleOtherSpecify('sick')" onclick="event.stopPropagation()">
                                        <span class="label-text text-gray-700">Others (please specify)</span>
                                    </label>
                                    
                                    <input type="text" id="sickOtherSpecify" name="sickOtherSpecify" placeholder="Please specify" class="input input-bordered input-sm mt-1 ml-6 w-3/4 hidden border-gray-300 focus:border-blue-500" onclick="event.stopPropagation()">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end mt-6">
                        <button type="button" class="btn bg-blue-500 hover:bg-blue-600 text-white" onclick="nextStep(1)">
                            Next
                            <i class="fi-rr-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 2: Date Selection -->
                <div id="step2" class="hidden space-y-6">
                    <h3 class="font-medium text-lg text-gray-800">Date Selection</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-gray-700">Applied For</span>
                            </label>
                            <select class="select select-bordered border-gray-300 focus:border-blue-500 w-full">
                                <option disabled selected>Select option</option>
                                <option>Whole Day</option>
                                <option>Half Day (AM)</option>
                                <option>Half Day (PM)</option>
                            </select>
                        </div>
                        
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-gray-700">Number of Days</span>
                            </label>
                            <input type="number" min="1" step="1" name="numberOfDays" class="input input-bordered border-gray-300 focus:border-blue-500 w-full" placeholder="Enter number of days" readonly id="numberOfDays">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-gray-700">Start Date</span>
                            </label>
                            <input type="date" name="startDate" class="input input-bordered border-gray-300 focus:border-blue-500 w-full" id="startDate" onchange="validateDates()">
                        </div>
                        
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-gray-700">End Date</span>
                            </label>
                            <input type="date" name="endDate" class="input input-bordered border-gray-300 focus:border-blue-500 w-full" id="endDate" onchange="validateDates()">
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-gray-700 flex items-center">
                            <i class="fi-rr-info mr-2"></i>
                            <span>The number of days will be automatically calculated based on your selected dates.</span>
                        </p>
                    </div>
                    
                    <div class="flex justify-between mt-6">
                        <button type="button" class="btn btn-outline border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white" onclick="prevStep(2)">
                            <i class="fi-rr-arrow-left mr-2"></i>
                            Previous
                        </button>
                        <button type="button" class="btn bg-blue-500 hover:bg-blue-600 text-white" onclick="nextStep(2)">
                            Next
                            <i class="fi-rr-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 3: Details -->
                <div id="step3" class="hidden space-y-6">
                    <h3 class="font-medium text-lg text-gray-800">Leave Details</h3>
                    
                    <!-- Where Leave Will Be Spent -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-800">Where Leave Will Be Spent</h4>
                        
                        <!-- For Vacation Leave -->
                        <div id="vacationLocation" class="space-y-3 hidden">
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="radio" name="locationType" value="philippines" class="radio radio-sm radio-primary" checked>
                                    <span class="label-text text-gray-700">Within the Philippines</span>
                                </label>
                            </div>
                            
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="radio" name="locationType" value="abroad" class="radio radio-sm radio-primary" onclick="toggleLocationSpecify('vacation')">
                                    <span class="label-text text-gray-700">Abroad (please specify)</span>
                                </label>
                                
                                <input type="text" id="locationSpecify" name="location_specify" class="input input-bordered input-sm mt-1 ml-6 w-3/4 hidden border-gray-300 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <!-- For Sick Leave -->
                        <div id="sickLocation" class="space-y-3 hidden">
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="radio" name="locationType" value="hospital" class="radio radio-sm radio-primary" checked>
                                    <span class="label-text text-gray-700">In Hospital</span>
                                </label>
                            </div>
                            
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="radio" name="locationType" value="outpatient" class="radio radio-sm radio-primary" onclick="toggleLocationSpecify('sick')">
                                    <span class="label-text text-gray-700">Outpatient (please specify)</span>
                                </label>
                                
                                <input type="text" id="locationSpecify" name="location_specify" class="input input-bordered input-sm mt-1 ml-6 w-3/4 hidden border-gray-300 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Commutation -->
                    <div class="space-y-4 pt-4 border-t border-gray-200">
                        <h4 class="font-medium text-gray-800">Commutation</h4>
                        
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-2">
                                <input type="radio" name="commutation" value="1" class="radio radio-sm radio-primary">
                                <span class="label-text text-gray-700">Requested</span>
                            </label>
                        </div>
                        
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-2">
                                <input type="radio" name="commutation" value="0" class="radio radio-sm radio-primary">
                                <span class="label-text text-gray-700">Not Requested</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-between mt-6">
                        <button type="button" class="btn btn-outline border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white" onclick="prevStep(3)">
                            <i class="fi-rr-arrow-left mr-2"></i>
                            Previous
                        </button>
                        <button type="button" class="btn bg-blue-500 hover:bg-blue-600 text-white" onclick="nextStep(3)">
                            Next
                            <i class="fi-rr-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 4: Confirmation -->
                <div id="step4" class="hidden space-y-6">
                    <!-- Confirmation header -->
                    <h3 class="font-medium text-lg text-gray-800">Review Your Leave Request</h3>
                    
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gray-100 p-4 border-b border-gray-300">
                            <h4 class="text-center font-bold text-gray-800 text-lg">DETAILS OF APPLICATION</h4>
                        </div>
                        
                        <!-- Form Content -->
                        <div class="grid grid-cols-1 md:grid-cols-2">
                            <!-- Left Column -->
                            <div class="p-4 border-r border-gray-300">
                                <div class="mb-6">
                                    <h5 class="font-bold text-gray-800 mb-2">TYPE OF LEAVE</h5>
                                    <div class="pl-4">
                                        <p class="text-gray-700">
                                            <span id="reviewLeaveType">-</span>
                                            <span id="reviewSubtype" class="text-sm text-gray-600 ml-2"></span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mb-6">
                                    <h5 class="font-bold text-gray-800 mb-2">NUMBER OF WORKING DAYS:</h5>
                                    <p class="pl-4 text-gray-700" id="reviewNumberOfDays">-</p>
                                </div>
                                
                                <div class="mb-6">
                                    <h5 class="font-bold text-gray-800 mb-2">Inclusive Dates:</h5>
                                    <p class="pl-4 text-gray-700" id="reviewDateRange">-</p>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="p-4">
                                <div class="mb-6">
                                    <h5 class="font-bold text-gray-800 mb-2">WHERE LEAVE WILL BE SPENT</h5>
                                    <div class="pl-4">
                                        <p id="confirmLeaveLocation" class="text-gray-700">-</p>
                                    </div>
                                </div>
                                
                                <div class="mb-6">
                                    <h5 class="font-bold text-gray-800 mb-2">COMMUTATION:</h5>
                                    <div class="pl-4" id="confirmCommutation">
                                        <!-- Will be filled by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between mt-6">
                        <button type="button" class="btn btn-outline border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white" onclick="prevStep(4)">
                            <i class="fi-rr-arrow-left mr-2"></i>
                            Previous
                        </button>
                        <button type="button" onclick="showConfirmModal()" class="btn bg-blue-500 hover:bg-blue-600 text-white">
                            Submit Request
                            <i class="fi-rr-check ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Success Alert Modal -->
    <div id="successModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
        <div class="modal-box bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
            <h3 class="font-bold text-lg text-green-600"><i class="fi-rr-check mr-2"></i>Success!</h3>
            <p class="py-4" id="successMessage">Your leave request has been submitted successfully.</p>
            <div class="modal-action flex justify-end">
                <a href="{{ route('employee.leave.history') }}" class="btn bg-blue-500 hover:bg-blue-600 text-white">View Leave History</a>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50" style="display: none;">
        <div class="modal-box bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
            <h3 class="font-bold text-lg">Confirm Submission</h3>
            <p class="py-4">Are you sure you want to submit this leave request?</p>
            <div class="modal-action flex justify-end space-x-2">
                <button type="button" id="cancelSubmit" onclick="hideConfirmModal()" class="btn btn-outline">Cancel</button>
                <button type="button" id="confirmSubmit" onclick="submitLeaveRequest()" class="btn bg-blue-500 hover:bg-blue-600 text-white">Yes, Submit</button>
            </div>
        </div>
    </div>
    
    <script>
    // Function to calculate the number of days between two dates
    function calculateDays() {
        try {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const numberOfDaysInput = document.getElementById('numberOfDays');
            
            if (!startDate || !endDate) {
                numberOfDaysInput.value = '';
                return;
            }
            
            const start = new Date(startDate);
            const end = new Date(endDate);
            
            // Calculate the difference in days
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 to include both start and end dates
            
            // Update the number of days input with whole number
            numberOfDaysInput.value = Math.floor(diffDays);
            
        } catch (error) {
            console.error('Error calculating days:', error);
        }
    }
    
    // Function to validate dates based on leave type
    function validateDates() {
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const leaveType = document.querySelector('input[name="leaveType"]:checked')?.value;
        
        if (!startDateInput || !endDateInput) return;
        
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (!startDate || !endDate) return;
        
        const start = new Date(startDate);
        const end = new Date(endDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Reset time to start of day
        
        // Check if end date is before start date
        if (end < start) {
            showError('End date cannot be earlier than start date', 'step2');
            return false;
        }
        
        // Check if start date is in the past
        if (start < today) {
            showError('Start date cannot be in the past', 'step2');
            return false;
        }
        
        // Vacation leave specific validation (5 days advance notice)
        if (leaveType === 'vacation') {
            const daysDifference = Math.ceil((start - today) / (1000 * 60 * 60 * 24));
            
            if (daysDifference < 5) {
                showError('Vacation leave must be applied at least 5 days before the start date', 'step2');
                return false;
            }
        }
        
        // If all validations pass, calculate days
        calculateDays();
        return true;
    }
    
    // Function to update date restrictions based on leave type
    function updateDateRestrictions(leaveType) {
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const today = new Date().toISOString().split('T')[0];
        
        if (!startDateInput || !endDateInput) return;
        
        if (leaveType === 'vacation') {
            // For vacation leave, set minimum start date to 5 days from today
            const minVacationDate = new Date();
            minVacationDate.setDate(minVacationDate.getDate() + 5);
            const minVacationDateStr = minVacationDate.toISOString().split('T')[0];
            
            startDateInput.min = minVacationDateStr;
            // If current start date is less than minimum, update it
            if (startDateInput.value && startDateInput.value < minVacationDateStr) {
                startDateInput.value = minVacationDateStr;
                // Also update end date if it's before the new start date
                if (endDateInput.value < minVacationDateStr) {
                    endDateInput.value = minVacationDateStr;
                }
            }
        } else if (leaveType === 'sick') {
            // For sick leave, allow dates from today onwards
            startDateInput.min = today;
        }
        
        // Re-validate dates after changing restrictions
        validateDates();
    }
    
    // Function to show/hide location specification input
    function toggleLocationSpecify(leaveType) {
        try {
            // Get all location specify inputs (they have the same ID currently)
            const locationSpecifyInputs = document.querySelectorAll('input[name="location_specify"]');
            const radioValue = document.querySelector(`input[name="locationType"]:checked`).value;
            
            // Determine which location specify input to show based on the leave type
            let targetInput = null;
            if (leaveType === 'vacation') {
                // Get the input within the vacation location section
                targetInput = document.querySelector('#vacationLocation input[name="location_specify"]');
            } else if (leaveType === 'sick') {
                // Get the input within the sick location section
                targetInput = document.querySelector('#sickLocation input[name="location_specify"]');
            }
            
            if (!targetInput) {
                console.error('Could not find location specify input for', leaveType);
                return;
            }
            
            // Show/hide the appropriate input
            if ((leaveType === 'vacation' && radioValue === 'abroad') || 
                (leaveType === 'sick' && radioValue === 'outpatient')) {
                targetInput.classList.remove('hidden');
                targetInput.placeholder = leaveType === 'vacation' ? 'Please specify country' : 'Please specify location';
            } else {
                targetInput.classList.add('hidden');
                targetInput.value = ''; // Clear the value when hiding
            }
            
            // Log for debugging
            console.log('toggleLocationSpecify called with:', leaveType, 'radio value:', radioValue, 'showing:', !targetInput.classList.contains('hidden'));
        } catch (error) {
            console.error('Error in toggleLocationSpecify:', error);
        }
    }
    
    // Function to show/hide sub-types based on leave type selection
    function showSubtype(leaveType) {
        // Hide all sub-type sections first
        document.querySelectorAll('[id$="Subtypes"]').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Show the selected leave type's sub-types
        const subtypeSection = document.getElementById(leaveType + 'Subtypes');
        if (subtypeSection) {
            subtypeSection.classList.remove('hidden');
        }
        
        // Uncheck all sub-type radios when changing leave type
        document.querySelectorAll(`input[name^="${leaveType}Subtype"]`).forEach(radio => {
            radio.checked = false;
        });
        
        // Hide all 'Other' text inputs
        document.querySelectorAll('[id$="OtherSpecify"]').forEach(el => {
            el.classList.add('hidden');
            el.required = false;
        });
        
        // Show/hide location sections based on leave type
        document.querySelectorAll('#vacationLocation, #sickLocation').forEach(el => {
            el.classList.add('hidden');
        });
        
        const locationSection = document.getElementById(leaveType + 'Location');
        if (locationSection) {
            locationSection.classList.remove('hidden');
            
            // Check the first radio button by default and update the input field
            const firstRadio = locationSection.querySelector('input[type="radio"]');
            if (firstRadio) {
                firstRadio.checked = true;
                // Hide the specification input by default
                const otherInput = document.getElementById('locationSpecify');
                if (otherInput) {
                    otherInput.classList.add('hidden');
                    otherInput.required = false;
                }
            }
        }
        
        // Update date restrictions based on leave type
        updateDateRestrictions(leaveType);
    }
    
    // Function to toggle 'Other' specification input
    function toggleOtherSpecify(leaveType) {
        const otherRadio = document.querySelector(`input[name="${leaveType}Subtype"][value="other"]`);
        const otherInput = document.getElementById(`${leaveType}OtherSpecify`);
        
        if (otherRadio && otherInput) {
            if (otherRadio.checked) {
                otherInput.classList.remove('hidden');
                otherInput.required = true;
            } else {
                otherInput.classList.add('hidden');
                otherInput.required = false;
                otherInput.value = '';
            }
        }
    }
    
    // Function to handle leave type container click
    function selectLeaveType(leaveType) {
        const radio = document.getElementById(leaveType + 'Leave');
        if (radio) {
            radio.checked = true;
            showSubtype(leaveType);
            updateDateRestrictions(leaveType);
        }
    }

    // Expose the nextStep and prevStep functions to the global scope
    window.nextStep = function(currentStep) {
        // Function implementation here
        console.log('Moving from step:', currentStep);
        try {
            // Validate current step
            if (currentStep < 1 || currentStep > 3) {
                throw new Error('Invalid current step: ' + currentStep);
            }

            // Validate required fields for each step before proceeding
            let isValid = validateStep(currentStep);
            if (!isValid) {
                console.log('Validation failed for step:', currentStep);
                return false;
            }

            // If moving to the confirmation step (step 4), update the review
            if (currentStep === 3) {
                console.log('Updating review section...');
                try {
                    updateReviewSection();
                    console.log('Review section updated successfully');
                } catch (updateError) {
                    console.error('Error updating review section:', updateError);
                    throw new Error('Failed to update review section: ' + updateError.message);
                }
            }
            
            // Hide current step
            const currentStepEl = document.getElementById('step' + currentStep);
            if (!currentStepEl) {
                throw new Error('Could not find current step element: step' + currentStep);
            }
            console.log('Hiding current step:', currentStep);
            currentStepEl.classList.add('hidden');
            
            // Show next step
            const nextStepNumber = currentStep + 1;
            const nextStepEl = document.getElementById('step' + nextStepNumber);
            if (!nextStepEl) {
                throw new Error('Could not find next step element: step' + nextStepNumber);
            }
            
            console.log('Showing next step:', nextStepNumber);
            nextStepEl.classList.remove('hidden');
            
            // Update step indicator
            const steps = document.querySelectorAll('.step');
            if (steps.length > currentStep) {
                console.log('Updating step indicator for step:', currentStep);
                steps[currentStep].classList.add('step-primary');
            }
            
            // Scroll to the top of the next step
            setTimeout(() => {
                nextStepEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
            
            console.log('Successfully moved to step:', nextStepNumber);
        } catch (error) {
            console.error('Error in nextStep:', error);
            alert('An error occurred while moving to the next step: ' + error.message);
        }
    };

    // Function to validate each step
    function getSelectedLeaveType() {
        const leaveTypeRadio = document.querySelector('input[name="leaveType"]:checked');
        return leaveTypeRadio ? leaveTypeRadio.value : null;
    }
    
    function validateStep(step) {
        // Remove any existing error messages
        const errorMessages = document.querySelectorAll('.validation-error');
        errorMessages.forEach(el => el.remove());
        
        let isValid = true;
        
        // Step 1: Leave Type validation
        if (step === 1) {
            // Check if a leave type is selected
            const leaveTypeSelected = document.querySelector('input[name="leaveType"]:checked');
            if (!leaveTypeSelected) {
                showValidationError('Please select a leave type', 'step1');
                isValid = false;
            } else {
                // Check if a subtype is selected when required
                const leaveType = leaveTypeSelected.value;
                const subtypeSection = document.getElementById(leaveType + 'Subtypes');
                
                if (subtypeSection && !subtypeSection.classList.contains('hidden')) {
                    const subtypeSelected = document.querySelector(`input[name="${leaveType}Subtype"]:checked`);
                    
                    if (!subtypeSelected) {
                        showValidationError('Please select a leave subtype', 'step1');
                        isValid = false;
                    } else if (subtypeSelected.value === 'other') {
                        // Check if 'Other' is specified
                        const otherSpecify = document.getElementById(`${leaveType}OtherSpecify`);
                        if (otherSpecify && otherSpecify.value.trim() === '') {
                            showValidationError('Please specify the other reason', 'step1');
                            isValid = false;
                        }
                    }
                }
            }
        }
        
        // Step 2: Date Selection validation
        else if (step === 2) {
            // Check if dates are selected
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');
            
            if (!startDate || !startDate.value) {
                showValidationError('Please select a start date', 'step2');
                isValid = false;
            }
            
            if (!endDate || !endDate.value) {
                showValidationError('Please select an end date', 'step2');
                isValid = false;
            }
            
            // Validate dates
            if (startDate && endDate && startDate.value && endDate.value) {
                if (!validateDates()) {
                    isValid = false;
                }
            }
            
            // Validate location based on leave type
            const leaveType = getSelectedLeaveType();
            if (leaveType) {
                const locationSection = document.getElementById(leaveType + 'Location');
                if (locationSection && !locationSection.classList.contains('hidden')) {
                    const locationSelected = locationSection.querySelector('input[name="locationType"]:checked');
                    
                    if (!locationSelected) {
                        showValidationError('Please select a location', 'step2');
                        isValid = false;
                    } else if ((locationSelected.value === 'abroad' || locationSelected.value === 'outpatient') && 
                              locationSection.querySelector('input[name="location_specify"]')?.value.trim() === '') {
                        showValidationError('Please specify the location', 'step2');
                        isValid = false;
                    }
                }
            }
        }
        
        // Step 3: Details validation
        else if (step === 3) {
            // Check if commutation is selected
            const commutationSelected = document.querySelector('input[name="commutation"]:checked');
            if (!commutationSelected) {
                showValidationError('Please select a commutation option', 'step3');
                isValid = false;
            }
        }
        
        return isValid;
    }
    
    // Helper function to show validation errors
    function showValidationError(message, stepId) {
        const step = document.getElementById(stepId);
        if (!step) return;
        
        // Create error message element
        const errorDiv = document.createElement('div');
        errorDiv.className = 'validation-error alert alert-error mt-4';
        errorDiv.innerHTML = `<div><i class="fi-rr-exclamation text-error"></i><span>${message}</span></div>`;
        
        // Find the button container in this step
        const buttonContainer = step.querySelector('.flex.justify-end');
        if (buttonContainer) {
            // Insert before the button container
            buttonContainer.parentNode.insertBefore(errorDiv, buttonContainer);
        } else {
            // Append to the end of the step if button container not found
            step.appendChild(errorDiv);
        }
        
        // Scroll to the error message
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    // Helper function to show general errors
    function showError(message, stepId) {
        // Remove any existing error messages
        const existingErrors = document.querySelectorAll('.validation-error');
        existingErrors.forEach(el => el.remove());
        
        showValidationError(message, stepId);
    }
    
    window.prevStep = function(currentStep) {
        console.log('Moving back from step:', currentStep);
        try {
            // Validate current step
            if (currentStep < 2 || currentStep > 4) {
                throw new Error('Cannot go back from step: ' + currentStep);
            }

            // Hide current step
            const currentStepEl = document.getElementById('step' + currentStep);
            if (!currentStepEl) {
                throw new Error('Could not find current step element: step' + currentStep);
            }
            console.log('Hiding current step:', currentStep);
            currentStepEl.classList.add('hidden');
            
            // Show previous step
            const prevStepNumber = currentStep - 1;
            const prevStepEl = document.getElementById('step' + prevStepNumber);
            if (!prevStepEl) {
                throw new Error('Could not find previous step element: step' + prevStepNumber);
            }
            console.log('Showing previous step:', prevStepNumber);
            prevStepEl.classList.remove('hidden');
            
            // Update step indicator
            const steps = document.querySelectorAll('.step');
            if (steps.length >= currentStep) {
                console.log('Updating step indicator for step:', currentStep);
                steps[currentStep - 1].classList.remove('step-primary');
            }
            
            // Scroll to the top of the previous step
            setTimeout(() => {
                prevStepEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
            
            console.log('Successfully moved back to step:', prevStepNumber);
        } catch (error) {
            console.error('Error in prevStep:', error);
            alert('An error occurred while going back to the previous step: ' + error.message);
        }
    };

    // Wrap the rest of the code in an IIFE
    (function() {
        // Helper function to format dates
        function formatDate(dateString) {
            if (!dateString) return '';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }
        
        function selectLeaveType(type) {
            // Select the radio button when clicking anywhere in the container
            document.getElementById(type + 'Leave').checked = true;
            showSubtype(type);
        }
        
        function showSubtype(type) {
            // Hide all subtypes first
            document.getElementById('vacationSubtypes').classList.add('hidden');
            document.getElementById('sickSubtypes').classList.add('hidden');
            document.getElementById('maternitySubtypes')?.classList?.add('hidden');
            document.getElementById('othersSubtypes')?.classList?.add('hidden');
            
            // Show the selected subtype
            if (document.getElementById(type + 'Leave').checked) {
                document.getElementById(type + 'Subtypes').classList.remove('hidden');
            }
        }
        
        function toggleOtherSpecify(type) {
            const radioButtons = document.getElementsByName(type + 'Subtype');
            const otherSpecifyInput = document.getElementById(type + 'OtherSpecify');
            
            // Check if "Other" is selected
            let otherSelected = false;
            for (const radio of radioButtons) {
                if (radio.value === 'other' && radio.checked) {
                    otherSelected = true;
                    break;
                }
            }
            
            // Show or hide the "Other specify" input field
            if (otherSelected) {
                otherSpecifyInput.classList.remove('hidden');
            } else {
                otherSpecifyInput.classList.add('hidden');
            }
        }
        
        // Note: toggleLocationSpecify is defined globally, not duplicated here
        
        function nextStep(currentStep) {
            console.log('Moving from step:', currentStep);
            try {
                // Validate current step
                if (currentStep < 1 || currentStep > 3) {
                    throw new Error('Invalid current step: ' + currentStep);
                }

                // If moving to the confirmation step (step 4), update the review
                if (currentStep === 3) {
                    console.log('Updating review section...');
                    try {
                        updateReviewSection();
                        console.log('Review section updated successfully');
                    } catch (updateError) {
                        console.error('Error updating review section:', updateError);
                        throw new Error('Failed to update review section: ' + updateError.message);
                    }
                }
                
                // Hide current step
                const currentStepEl = document.getElementById('step' + currentStep);
                if (!currentStepEl) {
                    throw new Error('Could not find current step element: step' + currentStep);
                }
                console.log('Hiding current step:', currentStep);
                currentStepEl.classList.add('hidden');
                
                // Show next step
                const nextStepNumber = currentStep + 1;
                const nextStepEl = document.getElementById('step' + nextStepNumber);
                if (!nextStepEl) {
                    throw new Error('Could not find next step element: step' + nextStepNumber);
                }
                
                console.log('Showing next step:', nextStepNumber);
                nextStepEl.classList.remove('hidden');
                
                // Update step indicator
                const steps = document.querySelectorAll('.step');
                if (steps.length > currentStep) {
                    console.log('Updating step indicator for step:', currentStep);
                    steps[currentStep].classList.add('step-primary');
                }
                
                // Scroll to the top of the next step
                setTimeout(() => {
                    nextStepEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
                
                // If moving to Step 3, show the appropriate location options based on leave type
                if (currentStep === 2) {
                    console.log('Configuring location options for leave type...');
                    const vacationSelected = document.getElementById('vacationLeave')?.checked || false;
                    const sickSelected = document.getElementById('sickLeave')?.checked || false;
                    const maternitySelected = document.getElementById('maternityLeave')?.checked || false;
                    const othersSelected = document.getElementById('othersLeave')?.checked || false;
                    
                    console.log('Leave type selection:', { vacationSelected, sickSelected, maternitySelected, othersSelected });
                    
                    const vacationLocation = document.getElementById('vacationLocation');
                    const sickLocation = document.getElementById('sickLocation');
                    const maternityLocation = document.getElementById('maternityLocation');
                    const othersLocation = document.getElementById('othersLocation');
                    
                    if (vacationLocation) {
                        vacationLocation.classList.toggle('hidden', !vacationSelected);
                        console.log('Vacation location visibility:', !vacationSelected ? 'hidden' : 'visible');
                        
                        // Check if abroad is selected and show/hide the specification field
                        if (vacationSelected) {
                            setTimeout(() => {
                                const abroadSelected = document.querySelector('#vacationLocation input[name="locationType"][value="abroad"]')?.checked || false;
                                if (abroadSelected) {
                                    console.log('Abroad is selected, showing specification field');
                                    toggleLocationSpecify('vacation');
                                }
                            }, 100);
                        }
                    }
                    if (sickLocation) {
                        sickLocation.classList.toggle('hidden', !sickSelected);
                        console.log('Sick location visibility:', !sickSelected ? 'hidden' : 'visible');
                        
                        // Check if outpatient is selected and show/hide the specification field
                        if (sickSelected) {
                            setTimeout(() => {
                                const outpatientSelected = document.querySelector('#sickLocation input[name="locationType"][value="outpatient"]')?.checked || false;
                                if (outpatientSelected) {
                                    console.log('Outpatient is selected, showing specification field');
                                    toggleLocationSpecify('sick');
                                }
                            }, 100);
                        }
                    }
                    if (maternityLocation) {
                        maternityLocation.classList.toggle('hidden', !maternitySelected);
                        console.log('Maternity location visibility:', !maternitySelected ? 'hidden' : 'visible');
                    }
                    if (othersLocation) {
                        othersLocation.classList.toggle('hidden', !othersSelected);
                        console.log('Others location visibility:', !othersSelected ? 'hidden' : 'visible');
                    }
                }
                
                console.log('Successfully moved to step:', nextStepNumber);
            } catch (error) {
                console.error('Error in nextStep:', error);
                alert('An error occurred while moving to the next step: ' + error.message);
            }
        }
        
        function prevStep(currentStep) {
            console.log('Moving back from step:', currentStep);
            try {
                // Validate current step
                if (currentStep < 2 || currentStep > 4) {
                    throw new Error('Cannot go back from step: ' + currentStep);
                }

                // Hide current step
                const currentStepEl = document.getElementById('step' + currentStep);
                if (!currentStepEl) {
                    throw new Error('Could not find current step element: step' + currentStep);
                }
                console.log('Hiding current step:', currentStep);
                currentStepEl.classList.add('hidden');
                
                // Show previous step
                const prevStepNumber = currentStep - 1;
                const prevStepEl = document.getElementById('step' + prevStepNumber);
                if (!prevStepEl) {
                    throw new Error('Could not find previous step element: step' + prevStepNumber);
                }
                console.log('Showing previous step:', prevStepNumber);
                prevStepEl.classList.remove('hidden');
                
                // Update step indicator
                const steps = document.querySelectorAll('.step');
                if (steps.length >= currentStep) {
                    console.log('Updating step indicator for step:', currentStep);
                    steps[currentStep - 1].classList.remove('step-primary');
                }
                
                // Scroll to the top of the previous step
                setTimeout(() => {
                    prevStepEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
                
                console.log('Successfully moved back to step:', prevStepNumber);
            } catch (error) {
                console.error('Error in prevStep:', error);
                alert('An error occurred while going back to the previous step: ' + error.message);
            }
        }
        
        // Make updateReviewSection available globally
        window.updateReviewSection = function() {
            try {
                // Update leave type and subtype in the form
                const leaveType = document.querySelector('input[name="leaveType"]:checked');
                if (leaveType) {
                    const leaveTypeText = leaveType.id.replace('Leave', '').charAt(0).toUpperCase() + 
                                      leaveType.id.replace('Leave', '').slice(1) + ' Leave';
                    const reviewLeaveType = document.getElementById('reviewLeaveType');
                    if (reviewLeaveType) reviewLeaveType.textContent = leaveTypeText;
                    
                    // Update subtype if exists
                    const subtypeRadio = document.querySelector(`input[name="${leaveType.value}Subtype"]:checked`);
                    if (subtypeRadio) {
                        let subtypeText = '';
                        if (subtypeRadio.value === 'other') {
                            const specifyInput = document.getElementById(`${leaveType.value}OtherSpecify`);
                            if (specifyInput?.value) {
                                subtypeText = `(${specifyInput.value})`;
                            }
                        } else {
                            subtypeText = `(${subtypeRadio.nextElementSibling?.textContent?.trim() || ''})`;
                        }
                        const reviewSubtype = document.getElementById('reviewSubtype');
                        if (reviewSubtype) reviewSubtype.textContent = subtypeText;
                    }
                }
                
                // Update date range and number of days in the form
                const startDate = document.getElementById('startDate')?.value;
                const endDate = document.getElementById('endDate')?.value;
                const reviewDateRange = document.getElementById('reviewDateRange');
                const reviewNumberOfDays = document.getElementById('reviewNumberOfDays');
                
                if (startDate && endDate && reviewDateRange && reviewNumberOfDays) {
                    reviewDateRange.textContent = `${formatDate(startDate)} to ${formatDate(endDate)}`;
                    const days = document.getElementById('numberOfDays')?.value || '0';
                    reviewNumberOfDays.textContent = `${days} day${days === '1' ? '' : 's'}`;
                }
                
                // Update location in the confirmation step
                const locationRadio = document.querySelector('input[name="locationType"]:checked');
                const confirmLeaveLocation = document.getElementById('confirmLeaveLocation');
                
                if (locationRadio && confirmLeaveLocation) {
                    let locationText = locationRadio.nextElementSibling?.textContent?.trim() || '';
                    
                    // Check if it's abroad or outpatient and add specification if available
                    if (locationRadio.value === 'abroad') {
                        // Get the vacation location specify input
                        const locationInput = document.querySelector('#vacationLocation input[name="location_specify"]');
                        if (locationInput?.value) {
                            locationText += `: ${locationInput.value}`;
                        }
                    } else if (locationRadio.value === 'outpatient') {
                        // Get the sick location specify input
                        const locationInput = document.querySelector('#sickLocation input[name="location_specify"]');
                        if (locationInput?.value) {
                            locationText += `: ${locationInput.value}`;
                        }
                    }
                    
                    // Update the location display in confirmation
                    console.log('Setting location text to:', locationText);
                    confirmLeaveLocation.textContent = locationText;
                }
                
                // Update commutation in the confirmation step
                const commutationRadio = document.querySelector('input[name="commutation"]:checked');
                const confirmCommutation = document.getElementById('confirmCommutation');
                
                if (commutationRadio && confirmCommutation) {
                    const commutationText = commutationRadio.nextElementSibling?.textContent?.trim() || '';
                    
                    // Update the commutation display in confirmation
                    console.log('Setting commutation text to:', commutationText);
                    confirmCommutation.textContent = commutationText;
                } else {
                    console.error('Could not find commutation radio or confirmation element');
                    console.log('Commutation radio:', commutationRadio);
                    console.log('Confirmation element:', confirmCommutation);
                }
            } catch (error) {
                console.error('Error in updateReviewSection:', error);
            }
            
            // Signature preview removed as per user request
        }
        
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }
        
        function calculateDays() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                // Calculate the time difference in milliseconds
                const timeDiff = end - start;
                
                // Convert to days and add 1 to include both start and end dates
                const daysDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24)) + 1;
                
                // Update the number of days field
                document.getElementById('numberOfDays').value = daysDiff;
            }
        }
    
        // Simple functions to show/hide modals
        window.showConfirmModal = function() {
            console.log('Show confirmation modal called');
            const modal = document.getElementById('confirmModal');
            console.log('Modal element:', modal);
            if (modal) {
                modal.style.display = 'flex';
                // Force browser reflow
                void modal.offsetHeight;
                // Add a class to ensure visibility
                modal.classList.add('modal-open');
                console.log('Modal display style set to:', modal.style.display);
            } else {
                console.error('Could not find confirmModal element');
            }
        }
        
        window.hideConfirmModal = function() {
            const modal = document.getElementById('confirmModal');
            if (modal) {
                modal.classList.remove('modal-open');
                modal.style.display = 'none';
            }
        }
        
        window.submitLeaveRequest = function() {
            console.log('Submitting leave request form');
            hideConfirmModal();
            
            // Process location data before submission
            const locationRadio = document.querySelector('input[name="locationType"]:checked');
            
            // Check if it's abroad or outpatient and use the specified location instead
            if (locationRadio && locationRadio.value === 'abroad') {
                const locationInput = document.querySelector('#vacationLocation input[name="location_specify"]');
                if (locationInput && locationInput.value.trim()) {
                    // Create a hidden input to override the location type with the specific country
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'location_specify';
                    hiddenInput.value = locationInput.value.trim();
                    document.getElementById('leaveRequestForm').appendChild(hiddenInput);
                    console.log('Added specific location to form:', locationInput.value.trim());
                }
            } else if (locationRadio && locationRadio.value === 'outpatient') {
                const locationInput = document.querySelector('#sickLocation input[name="location_specify"]');
                if (locationInput && locationInput.value.trim()) {
                    // Create a hidden input to override the location type with the specific location
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'location_specify';
                    hiddenInput.value = locationInput.value.trim();
                    document.getElementById('leaveRequestForm').appendChild(hiddenInput);
                    console.log('Added specific location to form:', locationInput.value.trim());
                }
            }
            
            document.getElementById('leaveRequestForm').submit();
        }
    })();
    
    // Confirmation dialog and form submission handling
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Setting up confirmation dialog handlers');
        const form = document.getElementById('leaveRequestForm');
        const confirmModal = document.getElementById('confirmModal');
        const confirmSubmit = document.getElementById('confirmSubmit');
        const cancelSubmit = document.getElementById('cancelSubmit');
        const successModal = document.getElementById('successModal');
        
        // Set up event handlers for the confirmation modal buttons
        if (cancelSubmit) {
            cancelSubmit.addEventListener('click', hideConfirmModal);
        }
        
        if (confirmSubmit) {
            confirmSubmit.addEventListener('click', submitLeaveRequest);
        }
        
        // Allow closing modals by clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === confirmModal) {
                hideConfirmModal();
            }
            
            if (e.target === successModal) {
                successModal.style.display = 'none';
            }
        });
        
        // Check for success message in session
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            console.log('Success parameter found in URL, showing success modal');
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = 'Your leave request has been submitted successfully.';
            successModal.style.display = 'flex';
        }
        
        // Initialize date fields and set up event handlers
        const today = new Date().toISOString().split('T')[0];
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        
        // Set min dates to prevent selecting past dates
        if (startDateInput) {
            startDateInput.min = today;
        }
        
        if (endDateInput) {
            endDateInput.min = today;
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
        
        // Set initial date restrictions based on default leave type (if any is selected)
        const selectedLeaveType = document.querySelector('input[name="leaveType"]:checked')?.value;
        if (selectedLeaveType) {
            updateDateRestrictions(selectedLeaveType);
        }
    });
    </script>
    <!-- Success Modal -->
    <div id="successModal" class="modal">
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
            // Check for success message in session
            @if(session('success'))
                console.log('Success message found in session, showing success modal');
                const successModal = document.getElementById('successModal');
                const successMessage = document.getElementById('successMessage');
                successMessage.textContent = "{{ session('success') }}";
                successModal.style.display = 'flex';
                
                // Automatically close the success modal after 5 seconds
                setTimeout(function() {
                    successModal.style.display = 'none';
                }, 5000); // 5000 milliseconds = 5 seconds
            @endif
        });
    </script>
</x-layouts.layout>

<!-- Removed signature-related code -->





