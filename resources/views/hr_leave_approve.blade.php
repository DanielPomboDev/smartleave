<x-layouts.layout>
    <x-slot:title>Approve Leave Request</x-slot:title>
    <x-slot:header>Approve Leave Request</x-slot:header>

    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                <i class="fi-rr-check-circle text-green-500 mr-2"></i>
                Leave Approval Process
            </h2>

            <!-- Step Indicator -->
            <div class="w-full py-4">
                <ul class="steps steps-horizontal w-full">
                    <li class="step step-primary">Review Request</li>
                    <li class="step" id="step2Indicator">Approval Decision</li>
                    <li class="step" id="step3Indicator">Review</li>
                </ul>
            </div>

            <form id="approvalForm" method="POST" action="{{ route('leave.approve.process', ['id' => $leaveId]) }}">
                @csrf

                <!-- Step 1: Review Request -->
                <div id="step1" class="space-y-6">
                    <h3 class="font-medium text-lg text-gray-800">Review Leave Request</h3>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 shadow-sm">
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
                                <p class="text-gray-600">{{ $leaveRequest->user->department->name ?? '' }} • {{ $leaveRequest->user->position }}</p>
                            </div>
                        </div>

                        <!-- Request Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                                <h5 class="font-semibold text-blue-600 mb-3">Type of Leave</h5>
                                <p class="font-medium text-gray-800 text-lg">{{ App\Models\LeaveRequest::LEAVE_TYPES[$leaveRequest->leave_type] ?? ucfirst($leaveRequest->leave_type) }}</p>
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

                        <!-- Recommendation Section -->
                        @php($deptRec = optional($leaveRequest->recommendations->sortByDesc('created_at')->first()))
                        @if($deptRec)
                        <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <h4 class="font-semibold text-blue-600 mb-3">Department Recommendation</h4>
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
                    </div>

                    <div class="flex justify-end mt-6">
                        @if($leaveRequest->status === App\Models\LeaveRequest::STATUS_RECOMMENDED)
                            <button type="button" class="btn bg-blue-500 hover:bg-blue-600 text-white"
                                onclick="nextStep(1)">
                                Next
                            </button>
                        @else
                            @if($leaveRequest->isCancelled())
                                <button type="button" class="btn" disabled>
                                    Cancelled
                                    <i class="fi-rr-ban ml-2"></i>
                                </button>
                            @else
                                <button type="button" class="btn" disabled>
                                    Next
                                    <i class="fi-rr-lock ml-2"></i>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Step 2: Approval Decision -->
                <div id="step2" class="hidden space-y-6">
                    <h3 class="font-medium text-lg text-gray-800">Approval Decision</h3>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h4 class="font-medium mb-4">Recommendation/Approval</h4>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Decision</span>
                            </label>
                            <div class="flex flex-col space-y-3">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="approval" value="approve"
                                        class="radio radio-success" checked onchange="toggleDecisionOptions()">
                                    <span>Approve</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="approval" value="disapprove"
                                        class="radio radio-error" onchange="toggleDecisionOptions()">
                                    <span>Disapprove</span>
                                </label>
                            </div>
                        </div>

                        <!-- Sub-options for Approval -->
                        <div id="approvalOptionsContainer" class="ml-6 border-l-2 border-green-200 pl-4 mb-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium">Approval Type:</span>
                                </label>
                                <div class="flex flex-col space-y-3">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                         <input type="radio" name="approved_for" value="with_pay"
                                            class="radio radio-sm radio-success" checked>
                                        <span>Approved for ___ days with pay</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                         <input type="radio" name="approved_for" value="without_pay"
                                            class="radio radio-sm radio-warning">
                                        <span>Approved for ___ days without pay</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                         <input type="radio" name="approved_for" value="other"
                                            class="radio radio-sm radio-info" onchange="toggleOtherApprovalInput()">
                                        <span>Others (specify):</span>
                                    </label>
                                    <div class="ml-6">
                                         <input type="text" name="approved_for_other"
                                            class="input input-bordered w-full"
                                            placeholder="Specify other approval details" disabled
                                            id="otherApprovalInput">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Disapproval Reason -->
                        <div id="disapprovalReasonContainer" class="ml-6 border-l-2 border-red-200 pl-4 hidden">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium">Reason for disapproval:</span>
                                </label>
                                 <textarea name="dissapproved_due_to" class="textarea textarea-bordered h-24"
                                    placeholder="Enter reason for disapproval..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button"
                            class="btn btn-outline border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white"
                            onclick="prevStep(2)">
                            Previous
                        </button>
                        @if($leaveRequest->status === App\Models\LeaveRequest::STATUS_RECOMMENDED)
                            <button type="button" class="btn bg-blue-500 hover:bg-blue-600 text-white"
                                onclick="nextStep(2)">
                                Next
                            </button>
                        @else
                            @if($leaveRequest->isCancelled())
                                <button type="button" class="btn" disabled>
                                    Cancelled
                                    <i class="fi-rr-ban ml-2"></i>
                                </button>
                            @else
                                <button type="button" class="btn" disabled>
                                    Next
                                    <i class="fi-rr-lock ml-2"></i>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Step 3: Review -->
                <div id="step3" class="hidden space-y-6">
                    <div class="card bg-white shadow-md mb-6">
                        <div class="card-body">
                            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Leave Final Review
                            </h2>
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
                                        <p class="text-gray-600">{{ $leaveRequest->user->department->name ?? '' }} • {{ $leaveRequest->user->position }}</p>
                                    </div>
                                </div>
                                <!-- Request Details -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                                        <h5 class="font-semibold text-blue-600 mb-3">Type of Leave</h5>
                                        <p class="font-medium text-gray-800 text-lg">{{ App\Models\LeaveRequest::LEAVE_TYPES[$leaveRequest->leave_type] ?? ucfirst($leaveRequest->leave_type) }}</p>
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
                            <!-- Recommendation Section -->
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
                            <!-- Approval Section -->
                            <div class="p-4 bg-white rounded-lg border border-green-200 shadow-sm mb-6">
                                <h4 class="font-semibold text-green-600 mb-3">HR Manager Approval</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">HR Personnel</label>
                                        <p class="font-medium text-gray-800" id="hrPersonnelName">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                                        <p class="text-sm text-gray-500" id="hrPersonnelPosition">{{ Auth::user()->position ?? 'HR Manager' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Decision</label>
                                        <p class="font-medium text-gray-800 text-capitalize" id="hrDecisionText">&mdash;</p>
                                        <p class="text-sm text-gray-500 mt-1" id="hrDecisionDetails">&nbsp;</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="flex justify-end mt-6">
                                @if($leaveRequest->status === App\Models\LeaveRequest::STATUS_RECOMMENDED)
                                    <button type="submit" class="btn bg-blue-500 hover:bg-blue-600 text-white">
                                        Submit
                                    </button>
                                @else
                                    @if($leaveRequest->isCancelled())
                                        <button type="button" class="btn" disabled>
                                            Cancelled
                                            <i class="fi-rr-ban ml-2"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn" disabled>
                                            Submit
                                            <i class="fi-rr-lock ml-2"></i>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        function nextStep(currentStep) {
            // Hide current step
            document.getElementById('step' + currentStep).classList.add('hidden');

            // Show next step
            document.getElementById('step' + (currentStep + 1)).classList.remove('hidden');

            // Update step indicator
            document.getElementById('step' + (currentStep + 1) + 'Indicator').classList.add('step-primary');

            if (currentStep === 2) {
                updateHRReviewSummary();
                updatePrintableForm();
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

        function toggleDecisionOptions() {
            const primaryDecision = document.querySelector('input[name="approval"]:checked').value;
            const approvalOptions = document.getElementById('approvalOptionsContainer');
            const disapprovalReason = document.getElementById('disapprovalReasonContainer');

            if (primaryDecision === 'approve') {
                approvalOptions.classList.remove('hidden');
                disapprovalReason.classList.add('hidden');
            } else {
                approvalOptions.classList.add('hidden');
                disapprovalReason.classList.remove('hidden');
            }
        }

        function updatePrintableForm() {
            // Set leave type checkbox
            document.getElementById('vacationCheckbox').innerHTML = '✓';

            // Set where leave will be spent
            document.getElementById('withinPhilippinesCheckbox').innerHTML = '✓';

            // Set commutation
            document.getElementById('commutationRequestedCheckbox').innerHTML = '✓';

            // Set working days and inclusive dates
            document.getElementById('workingDaysDisplay').textContent = '5';
            document.getElementById('inclusiveDatesDisplay').textContent = 'Jun 1-5, 2023';

            // Set recommendation based on decision
            const primaryDecision = document.querySelector('input[name="approval"]:checked').value;
            if (primaryDecision === 'approve') {
                document.getElementById('approvalRecommendationCheckbox').innerHTML = '✓';
                document.getElementById('disapprovalRecommendationCheckbox').innerHTML = '';
            } else {
                document.getElementById('approvalRecommendationCheckbox').innerHTML = '';
                document.getElementById('disapprovalRecommendationCheckbox').innerHTML = '✓';
            }
        }

        function updateHRReviewSummary() {
            const approval = document.querySelector('input[name="approval"]:checked').value;
            const approvedFor = document.querySelector('input[name="approved_for"]:checked');
            const otherDetails = document.querySelector('input[name="approved_for_other"]').value;
            const disReason = document.querySelector('textarea[name="dissapproved_due_to"]').value;

            const decisionText = document.getElementById('hrDecisionText');
            const decisionDetails = document.getElementById('hrDecisionDetails');

            decisionText.textContent = approval;
            if (approval === 'approve') {
                if (approvedFor) {
                    if (approvedFor.value === 'with_pay') {
                        decisionDetails.textContent = 'Approved for days with pay';
                    } else if (approvedFor.value === 'without_pay') {
                        decisionDetails.textContent = 'Approved for days without pay';
                    } else if (approvedFor.value === 'other') {
                        decisionDetails.textContent = otherDetails || 'Approved (details not specified)';
                    }
                }
            } else {
                decisionDetails.textContent = disReason || '—';
            }
        }

        function printForm() {
            // Update form before printing
            updatePrintableForm();
            
            // Create a new window for printing with proper page size
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Leave Application Form</title>
                    <style>
                        @page {
                            size: legal portrait;
                            margin: 0.5in;
                        }
                        body {
                            font-family: Arial, sans-serif;
                            font-size: 12pt;
                            line-height: 1.3;
                            margin: 0;
                            padding: 0;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            page-break-inside: avoid;
                        }
                        td, th {
                            border: 1px solid black;
                            padding: 4px;
                            vertical-align: top;
                        }
                        .text-center {
                            text-align: center;
                        }
                        h4 {
                            text-align: center;
                            margin: 10px 0 20px 0;
                            font-size: 16pt;
                            font-weight: bold;
                        }
                        .checkbox {
                            display: inline-block;
                            width: 12px;
                            height: 12px;
                            border: 1px solid black;
                            text-align: center;
                        }
                        strong {
                            font-weight: bold;
                        }
                        .signature-line {
                            border-top: 1px solid black;
                            display: inline-block;
                            min-width: 200px;
                            text-align: center;
                            margin-top: 20px;
                        }
                        .underline {
                            border-bottom: 1px solid black;
                            display: inline-block;
                            min-width: 150px;
                        }
                    </style>
                    <script>
                        window.onload = function() {
                            // Print after a short delay to ensure styles are loaded
                            setTimeout(function() {
                                window.print();
                            }, 500);
                        }
                    <\/script>
                </head>
                <body>
                    <h4>APPLICATION FOR LEAVE</h4>
                    ${document.getElementById('printableForm').innerHTML}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.focus();
        }

        function downloadPDF() {
            // Update form before generating PDF
            updatePrintableForm();
            
            // Get the form element
            const element = document.getElementById('printableForm');
            
            // Configure html2pdf options
            const opt = {
                margin: 0.5,
                filename: 'leave_application_form.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'in', format: 'legal', orientation: 'portrait' }
            };
            
            // Generate PDF
            html2pdf().set(opt).from(element).save();
        }
        
        // Initialize the form when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Set up initial state
            toggleDecisionOptions();
            
            // Add event listeners for approval type radios
            const approvalTypeRadios = document.querySelectorAll('input[name="approved_for"]');
            approvalTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const otherInput = document.getElementById('otherApprovalInput');
                    otherInput.disabled = this.value !== 'other';
                    
                    if (this.value === 'other') {
                        otherInput.focus();
                    }
                });
            });
        });
    </script>
</x-layouts.layout>



