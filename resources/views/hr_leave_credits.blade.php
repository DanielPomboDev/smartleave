<x-layouts.layout>
    <x-slot:title>Leave Records</x-slot:title>
    <x-slot:header>Leave Records</x-slot:header>
    
    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title text-xl font-bold text-gray-800">
                    <i class="fi-rr-time-past text-blue-500 mr-2"></i>
                    Leave Records
                </h2>
            </div>
            
            <!-- Filter Controls -->
            <div class="mb-6">
                <div class="flex flex-wrap items-end gap-4">
                    <!-- Department Filter -->
                    <div class="form-control w-52">
                        <label class="label py-1">
                            <span class="label-text font-medium text-gray-700">Department</span>
                        </label>
                        <select id="department-filter" class="select select-bordered w-full border-gray-300 focus:border-blue-500">
                            <option value="all">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $filters['department'] == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Leave Type Filter -->
                    <div class="form-control w-52">
                        <label class="label py-1">
                            <span class="label-text font-medium text-gray-700">Leave Type</span>
                        </label>
                        <select id="leave-type-filter" class="select select-bordered w-full border-gray-300 focus:border-blue-500">
                            <option value="all">All Types</option>
                            <option value="vacation">Vacation</option>
                            <option value="sick">Sick</option>
                            <option value="emergency">Emergency</option>
                        </select>
                    </div>
                    
                    <!-- Date Range Filter -->
                    <div class="form-control w-52">
                        <label class="label py-1">
                            <span class="label-text font-medium text-gray-700">Date Range</span>
                        </label>
                        <select id="date-range-filter" class="select select-bordered w-full border-gray-300 focus:border-blue-500">
                            <option value="all">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    
                    <!-- Search Employee -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium text-gray-700">Search</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="search-input" value="{{ $filters['search'] }}" placeholder="Search employee name" class="input input-bordered border-gray-300 focus:border-blue-500 w-full pr-10">
                            <button type="button" id="search-button" class="absolute right-2 top-1/2 -translate-y-1/2">
                                <i class="fi-rr-search text-gray-400"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Leave Records Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-gray-600">Employee</th>
                            <th class="text-gray-600">Position</th>
                            <th class="text-gray-600">Department</th>
                            <th class="text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-8 h-8">
                                        <span class="bg-blue-500 text-white text-xs font-bold flex items-center justify-center w-full h-full">
                                            DP
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Daniel Pombo</div>
                                    <div class="text-xs text-gray-500">EMP-001</div>
                                </div>
                            </td>
                            <td>IT Specialist</td>
                            <td>IT Department</td>
                            <td>
                                <button class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md border-none transition-colors duration-200 flex items-center justify-center gap-1" onclick="viewRecord()">
                                    <i class="fi-rr-eye"></i>
                                    <span>View Record</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-end mt-6">
                <div class="btn-group">
                    @if($leaveRecords->onFirstPage())
                        <button class="btn btn-sm" disabled>«</button>
                    @else
                        <a href="{{ $leaveRecords->previousPageUrl() }}" class="btn btn-sm">«</a>
                    @endif

                    @for($i = 1; $i <= $leaveRecords->lastPage(); $i++)
                        @if($i == $leaveRecords->currentPage())
                            <button class="btn btn-sm btn-active">{{ $i }}</button>
                        @else
                            <a href="{{ $leaveRecords->url($i) }}" class="btn btn-sm">{{ $i }}</a>
                        @endif
                    @endfor

                    @if($leaveRecords->hasMorePages())
                        <a href="{{ $leaveRecords->nextPageUrl() }}" class="btn btn-sm">»</a>
                    @else
                        <button class="btn btn-sm" disabled>»</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Function to update URL with filter parameters
        function updateFilters() {
            const department = document.getElementById('department-filter').value;
            const leaveType = document.getElementById('leave-type-filter').value;
            const dateRange = document.getElementById('date-range-filter').value;
            const search = document.getElementById('search-input').value;

            // Build query string
            const params = new URLSearchParams();
            if (department !== 'all') params.append('department', department);
            if (leaveType !== 'all') params.append('leave_type', leaveType);
            if (dateRange !== 'all') params.append('date_range', dateRange);
            if (search) params.append('search', search);

            // Update URL and reload page
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        // Add event listeners for filters
        document.getElementById('department-filter').addEventListener('change', updateFilters);
        document.getElementById('leave-type-filter').addEventListener('change', updateFilters);
        document.getElementById('date-range-filter').addEventListener('change', updateFilters);
        document.getElementById('search-button').addEventListener('click', updateFilters);
        document.getElementById('search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                updateFilters();
            }
        });

        // Function to view leave record
        function viewRecord() {
            window.location.href = "/leave-records";
        }
    </script>
</x-layouts.layout>













