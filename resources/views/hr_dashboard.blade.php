<x-layouts.layout>
    <x-slot:title>HR Manager Dashboard</x-slot:title>
    <x-slot:header>HR Dashboard</x-slot:header>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Pending Requests Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Pending Requests</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">12</h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <!-- Heroicon: Clock -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
            </div>
        </div>
        <!-- Approved This Month Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600">Approved This Month</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">28</h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <!-- Heroicon: Check Circle -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rejected This Month Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-600">Rejected This Month</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">5</h3>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <!-- Heroicon: X Circle -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12M12 2a10 10 0 100 20 10 10 0 000-20z" /></svg>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Employees Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">Total Employees</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">42</h3>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <!-- Heroicon: Users -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                </div>
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
                
                <a href="/hr-leave-requests" class="btn btn-sm btn-outline">
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
                                    <div class="text-xs text-gray-500">IT Department</div>
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
                                    <a href="{{ route('leave.approve.start', ['id' => 1]) }}" class="btn btn-xs btn-primary">
                                        View
                                    </a>
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
                            <td>May 22, 2023</td>
                            <td>1</td>
                            <td>
                                <span class="badge badge-success">Approved</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <a href="{{ route('leave.approve.start', ['id' => 1]) }}" class="btn btn-xs btn-primary">
                                        View
                                    </a>
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
                            <td>May 18-19, 2023</td>
                            <td>2</td>
                            <td>
                                <span class="badge badge-warning">Pending</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <a href="{{ route('leave.approve.start', ['id' => 1]) }}" class="btn btn-xs btn-primary">
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.layout>
