<x-layouts.layout>
    <x-slot:title>Department Admin Dashboard</x-slot:title>
    <x-slot:header>Department Dashboard</x-slot:header>
    
    <!-- Overview Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Pending Requests Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Pending Requests</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">8</h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fi-rr-hourglass-end text-xl text-blue-500"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <span class="text-blue-600">↑ 3</span> from last week
                </p>
            </div>
        </div>
        
        <!-- Approved This Month Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600">Approved This Month</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">15</h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fi-rr-check-circle text-xl text-green-500"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <span class="text-green-600">↑ 5</span> from last month
                </p>
            </div>
        </div>
        
        <!-- Rejected This Month Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-600">Rejected This Month</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">3</h3>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fi-rr-cross-circle text-xl text-red-500"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <span class="text-red-600">↓ 1</span> from last month
                </p>
            </div>
        </div>
        
        <!-- Department Employees Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">Department Employees</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">12</h3>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fi-rr-users text-xl text-purple-500"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <span class="text-purple-600">↑ 1</span> new this month
                </p>
            </div>
        </div>
    </div>
    
    <!-- Recent Leave Requests Table -->
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title text-xl font-bold text-gray-800">
                    <i class="fi-rr-time-past text-blue-500 mr-2"></i>
                    Recent Leave Requests
                </h2>
                
                <a href="/department-leave-requests" class="btn btn-sm btn-outline">
                    View All Requests
                    <i class="fi-rr-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-gray-600">Employee</th>
                            <th class="text-gray-600">Leave Type</th>
                            <th class="text-gray-600">Period</th>
                            <th class="text-gray-600">No. of Days</th>
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
                                    <div class="text-xs text-gray-500">IT Specialist</div>
                                </div>
                            </td>
                            <td>Vacation Leave</td>
                            <td>Jun 1-5, 2023</td>
                            <td>5</td>
                            <td>
                                <span class="badge badge-warning">Pending</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="btn btn-xs btn-ghost" title="View Details">
                                        <i class="fi-rr-eye text-blue-500"></i>
                                    </button>
                                    <button class="btn btn-xs btn-success" title="Approve">
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
                                    <div class="text-xs text-gray-500">IT Analyst</div>
                                </div>
                            </td>
                            <td>Sick Leave</td>
                            <td>May 22, 2023</td>
                            <td>1</td>
                            <td>
                                <span class="badge badge-success">Approved</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="btn btn-xs btn-ghost" title="View Details">
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
                                    <div class="text-xs text-gray-500">Web Developer</div>
                                </div>
                            </td>
                            <td>Emergency Leave</td>
                            <td>May 18-19, 2023</td>
                            <td>2</td>
                            <td>
                                <span class="badge badge-error">Rejected</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="btn btn-xs btn-ghost" title="View Details">
                                        <i class="fi-rr-eye text-blue-500"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-8 h-8">
                                        <span class="bg-orange-500 text-white text-xs font-bold flex items-center justify-center w-full h-full">AW</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Alice Williams</div>
                                    <div class="text-xs text-gray-500">System Administrator</div>
                                </div>
                            </td>
                            <td>Vacation Leave</td>
                            <td>May 10-12, 2023</td>
                            <td>3</td>
                            <td>
                                <span class="badge badge-success">Approved</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="btn btn-xs btn-ghost" title="View Details">
                                        <i class="fi-rr-eye text-blue-500"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.layout>


