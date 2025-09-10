<x-layouts.layout>
    <x-slot:title>Leave Record</x-slot:title>
    <x-slot:header>Leave Record</x-slot:header>
    
    <div class="card bg-white shadow-lg mb-6">
        <div class="card-body p-6">
            <!-- Employee Information Header -->
            <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="avatar mr-6">
                        <div class="mask mask-squircle w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600">
                            <span class="text-white text-2xl font-bold flex items-center justify-center w-full h-full">
                                {{ strtoupper(substr($employee->first_name ?? 'N', 0, 1) . substr($employee->last_name ?? 'A', 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $employee->first_name ?? 'First Name' }} {{ $employee->last_name ?? 'Last Name' }}</h2>
                        <p class="text-lg text-gray-600 mb-1">{{ $employee->department->name ?? 'Department' }} • {{ $employee->position ?? 'Position' }}</p>
                        <p class="text-sm text-gray-500">Employee ID: {{ $employee->user_id ?? 'ID' }}</p>
                    </div>
                </div>
                <button onclick="openAddUndertimeModal()" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Add Undertime
                </button>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200 shadow-sm">
                    <h3 class="font-semibold text-blue-800 text-lg mb-4">Vacation Leave Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">Total Earned:</span>
                            <span class="font-medium text-blue-900">{{ number_format($vacationSummary['earned'] ?? 0, 3) }} days</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">Total Used:</span>
                            <span class="font-medium text-blue-900">{{ number_format($vacationSummary['used'] ?? 0, 3) }} days</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">Current Balance:</span>
                            <span class="font-medium text-blue-900">{{ number_format($vacationSummary['balance'] ?? 0, 3) }} days</span>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200 shadow-sm">
                    <h3 class="font-semibold text-green-800 text-lg mb-4">Sick Leave Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-green-700">Total Earned:</span>
                            <span class="font-medium text-green-900">{{ number_format($sickSummary['earned'] ?? 0, 3) }} days</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-green-700">Total Used:</span>
                            <span class="font-medium text-green-900">{{ number_format($sickSummary['used'] ?? 0, 3) }} days</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-green-700">Current Balance:</span>
                            <span class="font-medium text-green-900">{{ number_format($sickSummary['balance'] ?? 0, 3) }} days</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Leave Records Section -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Leave Records</h3>
                    <div class="flex gap-2">
                        <!-- Filter Dropdown -->
                        <div class="dropdown dropdown-end">
                            <button class="btn btn-sm btn-outline">
                                <i class="fas fa-filter mr-1"></i>
                                Filter
                                <i class="fas fa-angle-down ml-1"></i>
                            </button>
                            <div class="dropdown-content bg-white shadow-lg rounded-lg border border-gray-200 p-4 w-64 z-50">
                                <h4 class="font-medium text-gray-800 mb-3">Filter Options</h4>
                                
                                <!-- Month Filter -->
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                                    <select id="monthFilter" class="select select-bordered w-full text-sm">
                                        <option value="">All Months</option>
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                                
                                <!-- Year Filter -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                                    <select id="yearFilter" class="select select-bordered w-full text-sm">
                                        <option value="">All Years</option>
                                        <option value="2024">2024</option>
                                        <option value="2023">2023</option>
                                        <option value="2022">2022</option>
                                        <option value="2021">2021</option>
                                    </select>
                                </div>
                                
                                <!-- Filter Actions -->
                                <div class="flex gap-2">
                                    <button onclick="applyFilters()" class="btn btn-sm btn-primary flex-1">
                                        <i class="fi-rr-check mr-1"></i>
                                        Apply
                                    </button>
                                    <button onclick="clearFilters()" class="btn btn-sm btn-outline flex-1">
                                        <i class="fi-rr-cross mr-1"></i>
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($leaveRecords) && $leaveRecords->isNotEmpty())
                    @foreach($leaveRecords as $year => $yearlyRecords)
                        @foreach($yearlyRecords as $record)
                        <!-- {{ $record->month_year }} -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h4 class="text-gray-800 font-semibold text-lg">{{ $record->month_year }}</h4>
                            </div>
                            <div class="p-6 space-y-4">
                                <!-- Leave Entries -->
                                @if($record->formatted_vacation_entries)
                                    @foreach($record->formatted_vacation_entries as $vacation)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-umbrella-beach text-gray-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">Vacation Leave</p>
                                                <p class="text-sm text-gray-600">{{ $vacation['days'] }} days</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">For: {{ $vacation['start_date'] ?? '' }} - {{ $vacation['end_date'] ?? '' }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif

                                @if($record->formatted_sick_entries)
                                    @foreach($record->formatted_sick_entries as $sick)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-thermometer-half text-gray-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">Sick Leave</p>
                                                <p class="text-sm text-gray-600">{{ $sick['days'] }} days</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">For: {{ $sick['date'] ?? '' }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif

                                @if($record->undertime_hours > 0)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-clock text-gray-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">Undertime</p>
                                            <p class="text-sm text-gray-600">{{ $record->formatted_undertime }} days</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">For: {{ $record->month_name }} 1 - {{ cal_days_in_month(CAL_GREGORIAN, $record->month, $record->year) }}, {{ $record->year }}</p>
                                    </div>
                                </div>
                                @endif

                                <!-- Monthly Balance -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <h5 class="font-medium text-gray-800 mb-2">Vacation Leave</h5>
                                        <div class="space-y-1 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Earned:</span>
                                                <span class="font-medium text-gray-800">{{ number_format($record->vacation_earned, 3) }} days</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Used:</span>
                                                <span class="font-medium text-gray-800">{{ number_format($record->vacation_used, 3) }} days</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Balance:</span>
                                                <span class="font-medium text-gray-800">{{ number_format($record->vacation_balance, 3) }} days</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <h5 class="font-medium text-gray-800 mb-2">Sick Leave</h5>
                                        <div class="space-y-1 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Earned:</span>
                                                <span class="font-medium text-gray-800">{{ number_format($record->sick_earned, 3) }} days</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Used:</span>
                                                <span class="font-medium text-gray-800">{{ number_format($record->sick_used, 3) }} days</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Balance:</span>
                                                <span class="font-medium text-gray-800">{{ number_format($record->sick_balance, 3) }} days</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                @else
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden p-8 text-center">
                        <i class="fas fa-file-alt text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-xl font-medium text-gray-700 mb-2">No Leave Records Found</h3>
                        <p class="text-gray-500">There are no leave records for this employee yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Undertime Modal -->
    <div id="addUndertimeModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add Monthly Undertime</h3>
            <form id="addUndertimeForm" class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Month</span>
                    </label>
                    <select class="select select-bordered w-full" name="month" required>
                        <option value="">Select Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Year</span>
                    </label>
                    <select class="select select-bordered w-full" name="year" required>
                        <option value="">Select Year</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Undertime Duration</span>
                    </label>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="label">
                                <span class="label-text text-sm">Hours</span>
                            </label>
                            <input type="number" name="hours" min="0" max="23" class="input input-bordered w-full" placeholder="00" required>
                        </div>
                        <div class="flex-1">
                            <label class="label">
                                <span class="label-text text-sm">Minutes</span>
                            </label>
                            <input type="number" name="minutes" min="0" max="59" class="input input-bordered w-full" placeholder="00" required>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500 mt-2">Calculated Days: <span id="calculatedDays">0.000</span> days</div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeAddUndertimeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Undertime</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Conversion table for hours to days
        const hoursToDays = {
            1: 0.125, 2: 0.250, 3: 0.375, 4: 0.500, 5: 0.625, 
            6: 0.750, 7: 0.875, 8: 1.000
        };

        // Conversion table for minutes to days
        const minutesToDays = {
            1: 0.002, 2: 0.004, 3: 0.006, 4: 0.008, 5: 0.010, 6: 0.012, 7: 0.015, 8: 0.017, 9: 0.019,
            10: 0.021, 11: 0.023, 12: 0.025, 13: 0.027, 14: 0.029, 15: 0.031, 16: 0.033, 17: 0.035, 18: 0.037, 19: 0.040,
            20: 0.042, 21: 0.044, 22: 0.046, 23: 0.048, 24: 0.050, 25: 0.052, 26: 0.054, 27: 0.056, 28: 0.058, 29: 0.060,
            30: 0.062, 31: 0.065, 32: 0.067, 33: 0.069, 34: 0.071, 35: 0.073, 36: 0.075, 37: 0.077, 38: 0.079, 39: 0.081,
            40: 0.083, 41: 0.085, 42: 0.087, 43: 0.090, 44: 0.092, 45: 0.094, 46: 0.096, 47: 0.098, 48: 0.100, 49: 0.102,
            50: 0.104, 51: 0.106, 52: 0.108, 53: 0.110, 54: 0.112, 55: 0.115, 56: 0.117, 57: 0.119, 58: 0.121, 59: 0.123, 60: 0.125
        };

        // Function to calculate days from hours and minutes
        function calculateDaysFromTime(hours, minutes) {
            let totalDays = 0;
            
            // Add hours conversion
            if (hours > 0) {
                totalDays += hoursToDays[hours] || 0;
            }
            
            // Add minutes conversion
            if (minutes > 0) {
                totalDays += minutesToDays[minutes] || 0;
            }
            
            return totalDays.toFixed(3);
        }

        // Function to update the calculated days display
        function updateCalculatedDays() {
            const hours = parseInt(document.querySelector('input[name="hours"]').value) || 0;
            const minutes = parseInt(document.querySelector('input[name="minutes"]').value) || 0;
            const calculatedDays = calculateDaysFromTime(hours, minutes);
            document.getElementById('calculatedDays').textContent = calculatedDays;
        }

        // Add event listeners to hours and minutes inputs
        document.addEventListener('DOMContentLoaded', function() {
            const hoursInput = document.querySelector('input[name="hours"]');
            const minutesInput = document.querySelector('input[name="minutes"]');
            
            if (hoursInput) {
                hoursInput.addEventListener('input', updateCalculatedDays);
            }
            
            if (minutesInput) {
                minutesInput.addEventListener('input', updateCalculatedDays);
            }
        });

        function openAddUndertimeModal() {
            document.getElementById('addUndertimeModal').classList.add('modal-open');
        }

        function closeAddUndertimeModal() {
            document.getElementById('addUndertimeModal').classList.remove('modal-open');
        }

        // Filter functionality
        function applyFilters() {
            const selectedMonth = document.getElementById('monthFilter').value;
            const selectedYear = document.getElementById('yearFilter').value;
            
            // Get all month cards
            const monthCards = document.querySelectorAll('[class*="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"]');
            
            monthCards.forEach(card => {
                const monthHeader = card.querySelector('h4');
                if (monthHeader) {
                    const monthText = monthHeader.textContent;
                    let shouldShow = true;
                    
                    // Check month filter
                    if (selectedMonth) {
                        const monthNames = [
                            'January', 'February', 'March', 'April', 'May', 'June',
                            'July', 'August', 'September', 'October', 'November', 'December'
                        ];
                        const expectedMonth = monthNames[parseInt(selectedMonth) - 1];
                        if (!monthText.includes(expectedMonth)) {
                            shouldShow = false;
                        }
                    }
                    
                    // Check year filter
                    if (selectedYear && !monthText.includes(selectedYear)) {
                        shouldShow = false;
                    }
                    
                    // Show/hide card
                    card.style.display = shouldShow ? 'block' : 'none';
                }
            });
            
            // Show filter status
            showFilterStatus(selectedMonth, selectedYear);
        }

        function clearFilters() {
            // Reset dropdowns
            document.getElementById('monthFilter').value = '';
            document.getElementById('yearFilter').value = '';
            
            // Show all cards
            const monthCards = document.querySelectorAll('[class*="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"]');
            monthCards.forEach(card => {
                card.style.display = 'block';
            });
            
            // Hide filter status
            hideFilterStatus();
        }

        function showFilterStatus(month, year) {
            // Remove existing status if any
            hideFilterStatus();
            
            const statusDiv = document.createElement('div');
            statusDiv.id = 'filterStatus';
            statusDiv.className = 'bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4';
            
            let statusText = 'Showing records for: ';
            if (month) {
                const monthNames = [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];
                statusText += monthNames[parseInt(month) - 1];
            }
            if (year) {
                statusText += (month ? ' ' : '') + year;
            }
            if (!month && !year) {
                statusText = 'Showing all records';
            }
            
            statusDiv.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-blue-800 text-sm">${statusText}</span>
                    <button onclick="clearFilters()" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-times mr-1"></i>Clear
                    </button>
                </div>
            `;
            
            // Insert after the filter section
            const filterSection = document.querySelector('.flex.items-center.justify-between');
            filterSection.parentNode.insertBefore(statusDiv, filterSection.nextSibling);
        }

        function hideFilterStatus() {
            const existingStatus = document.getElementById('filterStatus');
            if (existingStatus) {
                existingStatus.remove();
            }
        }

        // Handle form submission
        document.getElementById('addUndertimeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Form submission started');
            
            // Get form values
            const month = document.querySelector('select[name="month"]').value;
            const year = document.querySelector('select[name="year"]').value;
            const hours = parseInt(document.querySelector('input[name="hours"]').value) || 0;
            const minutes = parseInt(document.querySelector('input[name="minutes"]').value) || 0;
            
            console.log('Form values:', { month, year, hours, minutes });
            
            // Validate required fields
            if (!month || !year) {
                showUndertimeErrorModal('Please select both month and year');
                return;
            }
            
            // Calculate undertime in days
            const undertimeDays = calculateDaysFromTime(hours, minutes);
            console.log('Calculated undertime days:', undertimeDays);
            
            // Prepare data for submission
            const formData = new FormData();
            formData.append('user_id', '{{ $employee->user_id }}');
            formData.append('month', month);
            formData.append('year', year);
            formData.append('undertime_hours', undertimeDays);
            formData.append('_token', '{{ csrf_token() }}');
            
            console.log('FormData prepared, sending request...');
            
            // Submit the data via AJAX
            fetch('/leave-records/add-undertime', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response received:', response);
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                if (data.success) {
                    // Close modal and show success message
                    closeAddUndertimeModal();
                    showUndertimeSuccessModal('Undertime added successfully!');
                    // Reload the page to show updated data after a short delay
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showUndertimeErrorModal('Error adding undertime: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showUndertimeErrorModal('Error adding undertime. Please try again.');
            });
        });

        // Function to show success modal
        function showUndertimeSuccessModal(message) {
            console.log('Showing success modal with message:', message);
            const modal = document.getElementById('undertimeSuccessModal');
            const messageElement = document.getElementById('undertimeSuccessMessage');
            if (modal && messageElement) {
                messageElement.textContent = message;
                modal.style.display = 'flex';
                modal.classList.add('modal-open');
                
                // Automatically close the modal after 3 seconds
                setTimeout(function() {
                    modal.style.display = 'none';
                    modal.classList.remove('modal-open');
                }, 3000);
            } else {
                console.error('Success modal elements not found');
                // Fallback to alert
                alert(message);
            }
        }

        // Function to show error modal
        function showUndertimeErrorModal(message) {
            console.log('Showing error modal with message:', message);
            const modal = document.getElementById('undertimeErrorModal');
            const messageElement = document.getElementById('undertimeErrorMessage');
            if (modal && messageElement) {
                messageElement.textContent = message;
                modal.style.display = 'flex';
                modal.classList.add('modal-open');
                
                // Automatically close the modal after 5 seconds
                setTimeout(function() {
                    modal.style.display = 'none';
                    modal.classList.remove('modal-open');
                }, 5000);
            } else {
                console.error('Error modal elements not found');
                // Fallback to alert
                alert('Error: ' + message);
            }
        }
    </script>

    <!-- Success Modal for Undertime -->
    <div id="undertimeSuccessModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-success">
                <i class="fas fa-check-circle mr-2"></i>
                Success!
            </h3>
            <p id="undertimeSuccessMessage" class="py-4">Undertime has been added successfully.</p>
            <div class="modal-action">
                <button onclick="document.getElementById('undertimeSuccessModal').style.display = 'none'; document.getElementById('undertimeSuccessModal').classList.remove('modal-open');" class="btn btn-primary">OK</button>
            </div>
        </div>
    </div>

    <!-- Error Modal for Undertime -->
    <div id="undertimeErrorModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-error">
                <i class="fas fa-exclamation-circle mr-2"></i>
                Error!
            </h3>
            <p id="undertimeErrorMessage" class="py-4">An error occurred while adding undertime.</p>
            <div class="modal-action">
                <button onclick="document.getElementById('undertimeErrorModal').style.display = 'none'; document.getElementById('undertimeErrorModal').classList.remove('modal-open');" class="btn btn-error">OK</button>
            </div>
        </div>
    </div>
</x-layouts.layout> 