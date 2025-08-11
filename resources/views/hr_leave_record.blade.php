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
                                DP
                            </span>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Daniel Pombo</h2>
                        <p class="text-lg text-gray-600 mb-1">IT Department • IT Specialist</p>
                        <p class="text-sm text-gray-500">Employee ID: EMP-001</p>
                    </div>
                </div>
                <button onclick="openAddUndertimeModal()" class="btn bg-blue-500 hover:bg-blue-600 text-white border-none">
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
                            <span class="font-medium text-blue-900">1.25 days</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">Total Used:</span>
                            <span class="font-medium text-blue-900">0.76 days</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">Current Balance:</span>
                            <span class="font-medium text-blue-900">6.188 days</span>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200 shadow-sm">
                    <h3 class="font-semibold text-green-800 text-lg mb-4">Sick Leave Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-green-700">Total Earned:</span>
                            <span class="font-medium text-green-900">1.25 days</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-green-700">Total Used:</span>
                            <span class="font-medium text-green-900">0 days</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-green-700">Current Balance:</span>
                            <span class="font-medium text-green-900">50.552 days</span>
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
                                <i class="fas fa-chevron-down ml-1"></i>
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
                                    <button onclick="applyFilters()" class="btn btn-sm bg-blue-500 hover:bg-blue-600 text-white border-none flex-1">
                                        <i class="fas fa-check mr-1"></i>
                                        Apply
                                    </button>
                                    <button onclick="clearFilters()" class="btn btn-sm btn-outline flex-1">
                                        <i class="fas fa-times mr-1"></i>
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-sm btn-outline">
                            <i class="fas fa-download mr-1"></i>
                            Export
                        </button>
                    </div>
                </div>

                <!-- September 2023 -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h4 class="text-gray-800 font-semibold text-lg">September 2023</h4>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Undertime Entry -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-gray-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Undertime</p>
                                    <p class="text-sm text-gray-600">00-04-50 hours</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">For: Sept. 1 - 30, 2023</p>
                            </div>
                        </div>
                        
                        <!-- Monthly Balance -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h5 class="font-medium text-gray-800 mb-2">Vacation Leave</h5>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Earned:</span>
                                        <span class="font-medium text-gray-800">1.25 days</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Balance:</span>
                                        <span class="font-medium text-gray-800">6.188 days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h5 class="font-medium text-gray-800 mb-2">Sick Leave</h5>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Earned:</span>
                                        <span class="font-medium text-gray-800">1.25 days</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Balance:</span>
                                        <span class="font-medium text-gray-800">50.552 days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- October 2023 -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h4 class="text-gray-800 font-semibold text-lg">October 2023</h4>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Undertime Entry -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-gray-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Undertime</p>
                                    <p class="text-sm text-gray-600">00-01-15 hours</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">For: Oct. 1 - 31, 2023</p>
                            </div>
                        </div>
                        
                        <!-- Monthly Balance -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h5 class="font-medium text-gray-800 mb-2">Vacation Leave</h5>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Earned:</span>
                                        <span class="font-medium text-gray-800">1.25 days</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Balance:</span>
                                        <span class="font-medium text-gray-800">7.282 days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h5 class="font-medium text-gray-800 mb-2">Sick Leave</h5>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Earned:</span>
                                        <span class="font-medium text-gray-800">1.25 days</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Balance:</span>
                                        <span class="font-medium text-gray-800">51.802 days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- November 2023 -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h4 class="text-gray-800 font-semibold text-lg">November 2023</h4>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Undertime Entry -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-gray-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Undertime</p>
                                    <p class="text-sm text-gray-600">00-01-12 hours</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">For: Nov. 1 - 30, 2023</p>
                            </div>
                        </div>
                        
                        <!-- Monthly Balance -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h5 class="font-medium text-gray-800 mb-2">Vacation Leave</h5>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Earned:</span>
                                        <span class="font-medium text-gray-800">1.25 days</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Balance:</span>
                                        <span class="font-medium text-gray-800">8.382 days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h5 class="font-medium text-gray-800 mb-2">Sick Leave</h5>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Earned:</span>
                                        <span class="font-medium text-gray-800">1.25 days</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Balance:</span>
                                        <span class="font-medium text-gray-800">53.052 days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- December 2023 -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h4 class="text-gray-800 font-semibold text-lg">December 2023</h4>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Vacation Leave Entry -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-umbrella-beach text-gray-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Vacation Leave</p>
                                    <p class="text-sm text-gray-600">2.50 days</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">For: Dec. 23-26, 2023</p>
                            </div>
                        </div>

                        <!-- Sick Leave Entry -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-thermometer-half text-gray-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Sick Leave</p>
                                    <p class="text-sm text-gray-600">1.00 day</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">For: Dec. 27, 2023</p>
                            </div>
                        </div>

                        <!-- Undertime Entry -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-gray-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Undertime</p>
                                    <p class="text-sm text-gray-600">00-01-02 hours</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">For: Dec. 1 - 31, 2023</p>
                            </div>
                        </div>

                        <!-- Monthly Balance -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h5 class="font-medium text-gray-800 mb-2">Vacation Leave</h5>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Earned:</span>
                                        <span class="font-medium text-gray-800">1.25 days</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Used:</span>
                                        <span class="font-medium text-gray-800">2.50 days</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Balance:</span>
                                        <span class="font-medium text-gray-800">5.938 days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h5 class="font-medium text-gray-800 mb-2">Sick Leave</h5>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Earned:</span>
                                        <span class="font-medium text-gray-800">1.25 days</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Used:</span>
                                        <span class="font-medium text-gray-800">1.00 day</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Balance:</span>
                                        <span class="font-medium text-gray-800">52.802 days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <select class="select select-bordered w-full" required>
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
                    <select class="select select-bordered w-full" required>
                        <option value="">Select Year</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
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
                    <div class="text-sm text-gray-500 mt-2">Format: (HH-MM) UT</div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeAddUndertimeModal()">Cancel</button>
                    <button type="submit" class="btn bg-blue-500 hover:bg-blue-600 text-white border-none">Add Undertime</button>
                </div>
            </form>
        </div>
    </div>

    <script>
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

        document.getElementById('addUndertimeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const hours = document.querySelector('input[name="hours"]').value.padStart(2, '0');
            const minutes = document.querySelector('input[name="minutes"]').value.padStart(2, '0');
            
            // Format the undertime as (HH-MM) UT
            const undertime = `(${hours}-${minutes}) UT`;
            
            // Here you would typically handle the form submission with the formatted undertime
            console.log('Undertime:', undertime);
            
            closeAddUndertimeModal();
        });
    </script>
</x-layouts.layout> 