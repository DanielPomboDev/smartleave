<x-layouts.layout>
    <x-slot:title>Leave Requests</x-slot:title>
    <x-slot:header>Leave Requests</x-slot:header>
    
    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                <i class="fi-rr-list-check text-blue-500 mr-2"></i>
                Manage Leave Requests
            </h2>
            
            <!-- Filter Controls -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Status</span>
                    </label>
                    <select class="select select-bordered border-gray-300 focus:border-blue-500 w-full">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="final_approval">Final Approval</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Department</span>
                    </label>
                    <select class="select select-bordered border-gray-300 focus:border-blue-500 w-full">
                        <option value="all">All Departments</option>
                        <option value="it">IT Department</option>
                        <option value="hr">HR Department</option>
                        <option value="finance">Finance Department</option>
                        <option value="operations">Operations</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Date Range</span>
                    </label>
                    <select class="select select-bordered border-gray-300 focus:border-blue-500 w-full">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Search</span>
                    </label>
                    <div class="relative">
                        <input type="text" placeholder="Search employee name" class="input input-bordered border-gray-300 focus:border-blue-500 w-full pr-10">
                        <button class="absolute right-2 top-1/2 -translate-y-1/2">
                            <i class="fi-rr-search text-gray-400"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Custom Date Range (Hidden by default) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 hidden" id="customDateRange">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">From Date</span>
                    </label>
                    <input type="date" class="input input-bordered border-gray-300 focus:border-blue-500 w-full">
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">To Date</span>
                    </label>
                    <input type="date" class="input input-bordered border-gray-300 focus:border-blue-500 w-full">
                </div>
            </div>
            
            <!-- Stats Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Pending</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">12</h3>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fi-rr-hourglass-end text-xl text-blue-500"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600">Approved</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">28</h3>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fi-rr-check-circle text-xl text-green-500"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-red-600">Rejected</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">5</h3>
                        </div>
                        <div class="bg-red-100 p-3 rounded-full">
                            <i class="fi-rr-cross-circle text-xl text-red-500"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Leave Requests Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-gray-600">Employee</th>
                            <th class="text-gray-600">Leave Type</th>
                            <th class="text-gray-600">Applied On</th>
                            <th class="text-gray-600">Period</th>
                            <th class="text-gray-600">Days</th>
                            <th class="text-gray-600">Status</th>
                            <th class="text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-8 h-8">
                                        <span class="bg-blue-500 text-white text-xs font-bold flex items-center justify-center w-full h-full">DP</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Daniel Pombo</div>
                                    <div class="text-xs text-gray-500">IT Department</div>
                                </div>
                            </td>
                            <td>Vacation Leave</td>
                            <td>May 15, 2023</td>
                            <td>Jun 1-5, 2023</td>
                            <td>5</td>
                            <td>
                                <span class="badge badge-warning">Pending</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="btn btn-xs btn-ghost" title="View Details" onclick="openLeaveModal('Daniel Pombo', 'Vacation Leave')">
                                        <i class="fi-rr-eye text-blue-500"></i>
                                    </button>
                                    <button class="btn btn-xs btn-success" title="Approve" onclick="window.location.href='{{ route('leave.approve.start', ['id' => 1]) }}'">
                                        <i class="fi-rr-check text-white"></i>
                                    </button>
                                    <button class="btn btn-xs btn-error" title="Reject">
                                        <i class="fi-rr-cross text-white"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-8 h-8">
                                        <span class="bg-green-500 text-white text-xs font-bold flex items-center justify-center w-full h-full">JS</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Jane Smith</div>
                                    <div class="text-xs text-gray-500">Finance Department</div>
                                </div>
                            </td>
                            <td>Sick Leave</td>
                            <td>May 20, 2023</td>
                            <td>May 22, 2023</td>
                            <td>1</td>
                            <td>
                                <span class="badge badge-success">Approved</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="btn btn-xs btn-ghost" title="View Details" onclick="openLeaveModal('Jane Smith', 'Sick Leave')">
                                        <i class="fi-rr-eye text-blue-500"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-8 h-8">
                                        <span class="bg-purple-500 text-white text-xs font-bold flex items-center justify-center w-full h-full">RJ</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Robert Johnson</div>
                                    <div class="text-xs text-gray-500">HR Department</div>
                                </div>
                            </td>
                            <td>Emergency Leave</td>
                            <td>May 17, 2023</td>
                            <td>May 18-19, 2023</td>
                            <td>2</td>
                            <td>
                                <span class="badge badge-warning">Pending</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="btn btn-xs btn-ghost" title="View Details" onclick="openLeaveModal('Robert Johnson', 'Emergency Leave')">
                                        <i class="fi-rr-eye text-blue-500"></i>
                                    </button>
                                    <button class="btn btn-xs btn-success" title="Approve" onclick="window.location.href='{{ route('leave.approve.start', ['id' => 1]) }}'">
                                        <i class="fi-rr-check text-white"></i>
                                    </button>
                                    <button class="btn btn-xs btn-error" title="Reject">
                                        <i class="fi-rr-cross text-white"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-8 h-8">
                                        <span class="bg-yellow-500 text-white text-xs font-bold flex items-center justify-center w-full h-full">MW</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Maria Williams</div>
                                    <div class="text-xs text-gray-500">Operations Department</div>
                                </div>
                            </td>
                            <td>Maternity Leave</td>
                            <td>Apr 10, 2023</td>
                            <td>May 1-30, 2023</td>
                            <td>30</td>
                            <td>
                                <span class="badge badge-success">Approved</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="btn btn-xs btn-ghost" title="View Details" onclick="openLeaveModal('Maria Williams', 'Maternity Leave')">
                                        <i class="fi-rr-eye text-blue-500"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-8 h-8">
                                        <span class="bg-red-500 text-white text-xs font-bold flex items-center justify-center w-full h-full">TB</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Thomas Brown</div>
                                    <div class="text-xs text-gray-500">IT Department</div>
                                </div>
                            </td>
                            <td>Sick Leave</td>
                            <td>May 12, 2023</td>
                            <td>May 13, 2023</td>
                            <td>1</td>
                            <td>
                                <span class="badge badge-error">Rejected</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="btn btn-xs btn-ghost" title="View Details" onclick="openLeaveModal('Thomas Brown', 'Sick Leave')">
                                        <i class="fi-rr-eye text-blue-500"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-end mt-6">
                <div class="btn-group">
                    <button class="btn btn-sm">«</button>
                    <button class="btn btn-sm btn-active">1</button>
                    <button class="btn btn-sm">2</button>
                    <button class="btn btn-sm">3</button>
                    <button class="btn btn-sm">»</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Leave Request Modal -->
    <div class="modal" id="leaveRequestModal">
        <div class="modal-box max-w-3xl">
            <h3 class="font-bold text-lg" id="modalTitle">Leave Request Details</h3>
            <button class="btn btn-sm btn-circle absolute right-2 top-2" onclick="closeLeaveModal()">✕</button>
            
            <div class="py-4">
                <!-- Employee Info -->
                <div class="flex items-center mb-6">
                    <div class="avatar mr-4">
                        <div class="w-16 rounded-full">
                            <div class="bg-blue-500 text-white text-lg font-bold flex items-center justify-center w-full h-full" id="employeeInitials">DP</div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold" id="employeeName">Daniel Pombo</h4>
                        <p class="text-gray-600">IT Department • Software Developer</p>
                    </div>
                </div>
                
                <!-- Leave Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h5 class="font-bold text-gray-700 mb-2">Leave Information</h5>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Leave Type</p>
                                    <p class="font-medium" id="leaveType">Vacation Leave</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <span class="badge badge-warning">Pending</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Applied On</p>
                                    <p class="font-medium">May 15, 2023</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Leave Period</p>
                                    <p class="font-medium">Jun 1-5, 2023</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">No. of Days</p>
                                    <p class="font-medium">5</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Half/Full Day</p>
                                    <p class="font-medium">Full Day</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-bold text-gray-700 mb-2">Leave Balance</h5>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Vacation Leave</p>
                                    <p class="font-medium">15 days</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Sick Leave</p>
                                    <p class="font-medium">12 days</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Emergency Leave</p>
                                    <p class="font-medium">3 days</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Remaining After</p>
                                    <p class="font-medium text-orange-500">10 days</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Reason and Attachments -->
                <div class="mb-6">
                    <h5 class="font-bold text-gray-700 mb-2">Reason for Leave</h5>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p>I am planning a family vacation to Boracay. This is a long-planned trip and all arrangements have been made. I have completed all pending tasks and have briefed my team about ongoing projects.</p>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h5 class="font-bold text-gray-700 mb-2">Attachments</h5>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fi-rr-file-pdf text-red-500 mr-2"></i>
                            <span>Flight_Booking.pdf</span>
                            <a href="#" class="ml-auto text-blue-500 hover:underline">
                                <i class="fi-rr-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- HR Action -->
                <div class="border-t border-gray-200 pt-6">
                    <h5 class="font-bold text-gray-700 mb-4">HR Action</h5>
                    
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-medium">Comments (Optional)</span>
                        </label>
                        <textarea class="textarea textarea-bordered h-24" placeholder="Add your comments here..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button class="btn btn-error">
                            <i class="fi-rr-cross mr-2"></i>
                            Reject
                        </button>
                        <button class="btn btn-success">
                            <i class="fi-rr-check mr-2"></i>
                            Approve
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Show/hide custom date range based on selection
        document.addEventListener('DOMContentLoaded', function() {
            const dateRangeSelect = document.querySelector('select[class*="select"]');
            const customDateRange = document.getElementById('customDateRange');
            
            if (dateRangeSelect && customDateRange) {
                dateRangeSelect.addEventListener('change', function() {
                    if (this.value === 'custom') {
                        customDateRange.classList.remove('hidden');
                    } else {
                        customDateRange.classList.add('hidden');
                    }
                });
            }
        });
        
        // Modal functions
        function openLeaveModal(name, leaveType) {
            const modal = document.getElementById('leaveRequestModal');
            const employeeName = document.getElementById('employeeName');
            const leaveTypeEl = document.getElementById('leaveType');
            const employeeInitials = document.getElementById('employeeInitials');
            
            if (modal && employeeName && leaveTypeEl && employeeInitials) {
                employeeName.textContent = name;
                leaveTypeEl.textContent = leaveType;
                
                // Generate initials
                const initials = name.split(' ').map(n => n[0]).join('');
                employeeInitials.textContent = initials;
                
                // Show modal
                modal.classList.add('modal-open');
            }
        }
        
        function closeLeaveModal() {
            const modal = document.getElementById('leaveRequestModal');
            if (modal) {
                modal.classList.remove('modal-open');
            }
        }
    </script>
</x-layouts.layout>


