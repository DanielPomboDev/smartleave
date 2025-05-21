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
                    <li class="step" id="step2Indicator">Certification of Leave Credits</li>
                    <li class="step" id="step3Indicator">Approval Decision</li>
                    <li class="step" id="step4Indicator">Printable Form</li>
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
                                <h5 class="font-semibold text-blue-600 mb-3">Number of Working Days</h5>
                                <p class="font-medium text-gray-800 text-lg">5</p>
                            </div>
                        </div>

                        <!-- Where Leave Will Be Spent -->
                        <div class="mb-6 p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <h4 class="font-semibold text-blue-600 mb-3">Where Leave Will Be Spent</h4>
                            <p class="font-medium text-gray-800">Within the Philippines (Boracay)</p>
                        </div>

                        <!-- Commutation -->
                        <div class="mb-6 p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <h4 class="font-semibold text-blue-600 mb-3">Commutation</h4>
                            <div class="flex items-center">
                                <span class="font-medium text-gray-800">Requested: </span>
                                <span
                                    class="ml-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Yes</span>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="mb-6 p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <h4 class="font-semibold text-blue-600 mb-3">Reason for Leave</h4>
                            <p class="font-medium text-gray-800">Family vacation planned months in advance.</p>
                        </div>

                        <!-- Recommendation Section -->
                        <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <h4 class="font-semibold text-blue-600 mb-3">Recommendation</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Authorized
                                        Personnel</label>
                                    <p class="font-medium text-gray-800">John Smith</p>
                                    <p class="text-sm text-gray-500">Department Head</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Approval Type</label>
                                    <p class="font-medium text-gray-800">Recommended for Approval</p>
                                </div>
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

                <!-- Step 2: Certification of Leave Credits -->
                <div id="step2" class="hidden space-y-6">
                    <h3 class="font-medium text-lg text-gray-800">Certification of Leave Credits</h3>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h4 class="font-medium mb-4">Leave Balance for Current Month</h4>

                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="text-gray-600">Leave Type</th>
                                        <th class="text-gray-600">Earned</th>
                                        <th class="text-gray-600">Used</th>
                                        <th class="text-gray-600">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Vacation Leave</td>
                                        <td>15</td>
                                        <td>5</td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td>Sick Leave</td>
                                        <td>15</td>
                                        <td>2</td>
                                        <td>13</td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td>30</td>
                                        <td>7</td>
                                        <td>23</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            <h4 class="font-medium mb-2">Certification</h4>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">I hereby certify that the above balance of leave credits is
                                        correct:</span>
                                </label>
                                <input type="text" name="certifier_name" class="input input-bordered"
                                    placeholder="Name of HR Officer">
                                <label class="label">
                                    <span class="label-text-alt">Human Resource Management Officer</span>
                                </label>
                            </div>
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
                            onclick="nextStep(2)">
                            Next
                            <i class="fi-rr-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Approval Decision -->
                <div id="step3" class="hidden space-y-6">
                    <h3 class="font-medium text-lg text-gray-800">Approval Decision</h3>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h4 class="font-medium mb-4">Recommendation/Approval</h4>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Decision</span>
                            </label>
                            <div class="flex flex-col space-y-3">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="primary_decision" value="approve"
                                        class="radio radio-success" checked onchange="toggleDecisionOptions()">
                                    <span>Approve</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="primary_decision" value="disapprove"
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
                                        <input type="radio" name="approval_type" value="with_pay"
                                            class="radio radio-sm radio-success" checked>
                                        <span>Approved for ___ days with pay</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="approval_type" value="without_pay"
                                            class="radio radio-sm radio-warning">
                                        <span>Approved for ___ days without pay</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="approval_type" value="other"
                                            class="radio radio-sm radio-info" onchange="toggleOtherApprovalInput()">
                                        <span>Others (specify):</span>
                                    </label>
                                    <div class="ml-6">
                                        <input type="text" name="other_approval_details"
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
                                <textarea name="disapproval_reason" class="textarea textarea-bordered h-24"
                                    placeholder="Enter reason for disapproval..."></textarea>
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
                        <button type="button" class="btn bg-blue-500 hover:bg-blue-600 text-white"
                            onclick="nextStep(3)">
                            Next
                            <i class="fi-rr-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Printable Form -->
                <div id="step4" class="hidden space-y-6">
                    <h3 class="font-medium text-lg text-gray-800">Printable Leave Form</h3>

                    <div class="bg-white p-6 rounded-lg border border-gray-200" id="printableForm">
                        <!-- Printable Form designed for legal size paper -->
                        <div class="text-center mb-4">
                            <h4 class="text-lg font-bold">APPLICATION FOR LEAVE</h4>
                        </div>

                        <table class="w-full border-collapse border border-gray-800">
                            <!-- Office/Agency and Name Row -->
                            <tr>
                                <td class="border border-gray-800 p-2 w-1/2">
                                    <strong>OFFICE/AGENCY</strong>
                                </td>
                                <td class="border border-gray-800 p-2 w-1/2">
                                    <strong>NAME (Last)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Given)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(M.I.)</strong>
                                </td>
                            </tr>

                            <!-- Date of Filing, Position, Salary Row -->
                            <tr>
                                <td class="border border-gray-800 p-2">
                                    <strong>Date of Filing</strong>
                                </td>
                                <td class="border border-gray-800 p-2">
                                    <div class="flex">
                                        <div class="w-1/2 border-r border-gray-800 p-2">
                                            <strong>Position</strong>
                                        </div>
                                        <div class="w-1/2 p-2">
                                            <strong>Salary (Monthly)</strong>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Details of Application Header -->
                            <tr>
                                <td colspan="2" class="border border-gray-800 p-2 text-center bg-gray-100">
                                    <strong>DETAILS OF APPLICATION</strong>
                                </td>
                            </tr>

                            <!-- Leave Type and Where Leave Will Be Spent -->
                            <tr>
                                <td class="border border-gray-800 p-2 align-top">
                                    <strong>TYPE OF LEAVE</strong><br>
                                    <div class="ml-2 mt-1">
                                        <div class="flex items-center mb-1">
                                            <div class="w-4 h-4 border border-gray-800 mr-2" id="vacationCheckbox"></div>
                                            <span>Vacation</span>
                                        </div>
                                        <div class="flex items-center mb-1">
                                            <div class="w-4 h-4 border border-gray-800 mr-2"></div>
                                            <span>To seek employment</span>
                                        </div>
                                        <div class="flex items-center mb-3">
                                            <div class="w-4 h-4 border border-gray-800 mr-2"></div>
                                            <span>Others specify</span>
                                        </div>

                                        <div class="flex items-center mb-1">
                                            <div class="w-4 h-4 border border-gray-800 mr-2"></div>
                                            <span>Sick</span>
                                        </div>
                                        <div class="ml-6">
                                            <div class="flex items-center mb-1">
                                                <div class="w-4 h-4 border border-gray-800 mr-2"></div>
                                                <span>Maternity</span>
                                            </div>
                                            <div class="flex items-center mb-3">
                                                <div class="w-4 h-4 border border-gray-800 mr-2"></div>
                                                <span>Others specify</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <strong>NUMBER OF WORKING DAYS:</strong><br>
                                            <div class="ml-2" id="workingDaysDisplay">5</div>
                                        </div>

                                        <div>
                                            <strong>Applied for:</strong><br>
                                            <div>Inclusive Dates:</div>
                                            <div id="inclusiveDatesDisplay">Jun 1-5, 2023</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border border-gray-800 p-2 align-top">
                                    <strong>WHERE LEAVE WILL BE SPENT</strong><br>
                                    <div class="ml-2 mt-1">
                                        <div class="mb-1">In case of vacation Leave</div>
                                        <div class="flex items-center mb-1">
                                            <div class="w-4 h-4 border border-gray-800 mr-2" id="withinPhilippinesCheckbox"></div>
                                            <span>Within the Philippines</span>
                                        </div>
                                        <div class="flex items-center mb-1">
                                            <div class="w-4 h-4 border border-gray-800 mr-2"></div>
                                            <span>Abroad (Specify)</span>
                                        </div>
                                        <div class="mb-3">
                                            <div>DECORUM</div>
                                            <div class="border-b border-gray-800 h-6"></div>
                                        </div>

                                        <div class="mb-1">In case of sick leave</div>
                                        <div class="flex items-center mb-1">
                                            <div class="w-4 h-4 border border-gray-800 mr-2"></div>
                                            <span>In hospital</span>
                                        </div>
                                        <div class="flex items-center mb-3">
                                            <div class="w-4 h-4 border border-gray-800 mr-2"></div>
                                            <span>Outpatient (Specify)</span>
                                        </div>

                                        <div class="mb-1"><strong>COMMUTATION:</strong></div>
                                        <div class="flex items-center mb-1">
                                            <div class="w-4 h-4 border border-gray-800 mr-2" id="commutationRequestedCheckbox"></div>
                                            <span>Requested</span>
                                        </div>
                                        <div class="flex items-center mb-1">
                                            <div class="w-4 h-4 border border-gray-800 mr-2"></div>
                                            <span>Not Requested</span>
                                        </div>

                                        <div class="text-right mt-4">
                                            <div class="border-t border-gray-800 inline-block pt-1">
                                                (Signature of Applicant)
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Details of Action on Application Header -->
                            <tr>
                                <td colspan="2" class="border border-gray-800 p-2 text-center bg-gray-100">
                                    <strong>DETAILS OF ACTION ON APPLICATION</strong>
                                </td>
                            </tr>

                            <!-- Certification and Recommendation -->
                            <tr>
                                <td class="border border-gray-800 p-2 align-top">
                                    <strong>CERTIFICATION OF LEAVE CREDITS</strong><br>
                                    <div class="mb-1">As of _______________</div>

                                    <table class="w-full border-collapse border border-gray-800 mb-4">
                                        <tr>
                                            <td class="border border-gray-800 p-1 text-center">Vacation</td>
                                            <td class="border border-gray-800 p-1 text-center">Sick</td>
                                            <td class="border border-gray-800 p-1 text-center">Leave Total</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-800 p-1 h-8"></td>
                                            <td class="border border-gray-800 p-1"></td>
                                            <td class="border border-gray-800 p-1"></td>
                                        </tr>
                                    </table>

                                    <div class="text-center mt-8 mb-2">
                                        <strong>MERLYN P. DERATAS</strong><br>
                                        HRMO II
                                    </div>
                                </td>
                                <td class="border border-gray-800 p-2 align-top">
                                    <strong>RECOMMENDATION:</strong><br>
                                    <div class="flex items-center mb-1 mt-1">
                                        <div class="w-4 h-4 border border-gray-800 mr-2" id="approvalRecommendationCheckbox"></div>
                                        <span>Approval</span>
                                    </div>
                                    <div class="flex items-start mb-4">
                                        <div class="w-4 h-4 border border-gray-800 mr-2 mt-1" id="disapprovalRecommendationCheckbox"></div>
                                        <div>
                                            <span>Disapproval due to</span>
                                            <div class="border-b border-gray-800 h-6 w-full"></div>
                                            <div class="border-b border-gray-800 h-6 w-full"></div>
                                        </div>
                                    </div>

                                    <div class="text-right mt-16">
                                        <div class="border-t border-gray-800 inline-block pt-1">
                                            (Authorized Official)
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Approval/Disapproval Section -->
                            <tr>
                                <td colspan="2" class="border border-gray-800 p-2">
                                    <div class="flex">
                                        <div class="w-1/2 pr-2">
                                            <strong>APPROVED FOR:</strong><br>
                                            <div class="flex items-center mb-1 mt-1">
                                                <div class="border-b border-gray-800 w-32 inline-block mr-2"></div>
                                                <span>Days with Pay</span>
                                            </div>
                                            <div class="flex items-center mb-1">
                                                <div class="border-b border-gray-800 w-32 inline-block mr-2"></div>
                                                <span>Days without Pay</span>
                                            </div>
                                            <div class="flex items-center mb-1">
                                                <div class="border-b border-gray-800 w-32 inline-block mr-2"></div>
                                                <span>Others (Specify)</span>
                                            </div>
                                        </div>
                                        <div class="w-1/2 pl-2">
                                            <strong>DISAPPROVED DUE TO:</strong><br>
                                            <div class="border-b border-gray-800 h-6 w-full mt-1"></div>
                                            <div class="border-b border-gray-800 h-6 w-full"></div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-8">
                                        <div class="border-t border-gray-800 inline-block pt-1 w-64">
                                            Signature
                                        </div>
                                        <div class="mt-1">
                                            <strong>HON. DENNIS P. ESTARON</strong><br>
                                            Municipal Mayor
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button"
                            class="btn btn-outline border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white"
                            onclick="prevStep(4)">
                            <i class="fi-rr-arrow-left mr-2"></i>
                            Previous
                        </button>
                        <div class="flex space-x-2">
                            <button type="button" class="btn bg-gray-500 hover:bg-gray-600 text-white"
                                onclick="printForm()">
                                <i class="fi-rr-print mr-2"></i>
                                Print Form
                            </button>
                            <button type="button" class="btn bg-red-500 hover:bg-red-600 text-white"
                                onclick="downloadPDF()">
                                <i class="fi-rr-file-pdf mr-2"></i>
                                Download PDF
                            </button>
                            <button type="submit" class="btn bg-green-500 hover:bg-green-600 text-white">
                                Submit
                                <i class="fi-rr-check ml-2"></i>
                            </button>
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

            // If moving to Step 4, update the printable form with the selected options
            if (currentStep === 3) {
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
            const primaryDecision = document.querySelector('input[name="primary_decision"]:checked').value;
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
            const primaryDecision = document.querySelector('input[name="primary_decision"]:checked').value;
            if (primaryDecision === 'approve') {
                document.getElementById('approvalRecommendationCheckbox').innerHTML = '✓';
                document.getElementById('disapprovalRecommendationCheckbox').innerHTML = '';
            } else {
                document.getElementById('approvalRecommendationCheckbox').innerHTML = '';
                document.getElementById('disapprovalRecommendationCheckbox').innerHTML = '✓';
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
            const approvalTypeRadios = document.querySelectorAll('input[name="approval_type"]');
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



