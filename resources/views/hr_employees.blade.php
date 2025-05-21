<x-layouts.layout>
    <x-slot:title>Employees</x-slot:title>
    <x-slot:header>Employees</x-slot:header>
    
    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title text-xl font-bold text-gray-800">
                    <i class="fi-rr-users text-blue-500 mr-2"></i>
                    Manage Employees
                </h2>
                
                <button class="btn btn-primary" onclick="openAddEmployeeModal()">
                    <i class="fi-rr-user-add mr-2"></i>
                    Add Employee
                </button>
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
                            <option value="it">IT Department</option>
                            <option value="hr">HR Department</option>
                            <option value="finance">Finance Department</option>
                            <option value="operations">Operations</option>
                        </select>
                    </div>
                    
                    <!-- Position Filter -->
                    <div class="form-control w-52">
                        <label class="label py-1">
                            <span class="label-text font-medium text-gray-700">Position</span>
                        </label>
                        <select id="position-filter" class="select select-bordered w-full border-gray-300 focus:border-blue-500">
                            <option value="all">All Positions</option>
                            <option value="manager">Manager</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="form-control w-52">
                        <label class="label py-1">
                            <span class="label-text font-medium text-gray-700">Status</span>
                        </label>
                        <select id="status-filter" class="select select-bordered w-full border-gray-300 focus:border-blue-500">
                            <option value="all">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <!-- Search Employee -->
                    <div class="form-control flex-grow">
                        <label class="label">
                            <span class="label-text font-medium text-gray-700">Search</span>
                        </label>
                        <div class="relative">
                            <input type="text" placeholder="Search by name, ID, or position" class="input input-bordered border-gray-300 focus:border-blue-500 w-full pr-10">
                            <button class="absolute right-2 top-1/2 -translate-y-1/2">
                                <i class="fi-rr-search text-gray-400"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Employees Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-gray-600">Employee</th>
                            <th class="text-gray-600">ID</th>
                            <th class="text-gray-600">Department</th>
                            <th class="text-gray-600">Position</th>
                            <th class="text-gray-600">Date Hired</th>
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
                                    <div class="text-xs text-gray-500">daniel.pombo@sanjulian.gov.ph</div>
                                </div>
                            </td>
                            <td>EMP-2023-001</td>
                            <td>IT Department</td>
                            <td>Software Developer</td>
                            <td>Jan 15, 2023</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="btn btn-xs btn-ghost" title="View Details">
                                        <i class="fi-rr-eye text-blue-500"></i>
                                    </button>
                                    <button class="btn btn-xs btn-ghost" title="Edit">
                                        <i class="fi-rr-edit text-green-500"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-8 h-8">
                                        <span class="bg-green-500 text-white text-xs font-bold flex items-center justify-center w-full h-full">MR</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Maria Rodriguez</div>
                                    <div class="text-xs text-gray-500">maria.rodriguez@sanjulian.gov.ph</div>
                                </div>
                            </td>
                            <td>EMP-2023-002</td>
                            <td>HR Department</td>
                            <td>HR Manager</td>
                            <td>Jan 10, 2015</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="btn btn-xs btn-ghost" title="View Details">
                                        <i class="fi-rr-eye text-blue-500"></i>
                                    </button>
                                    <button class="btn btn-xs btn-ghost" title="Edit">
                                        <i class="fi-rr-edit text-green-500"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-8 h-8">
                                        <span class="bg-purple-500 text-white text-xs font-bold flex items-center justify-center w-full h-full">JS</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Jane Smith</div>
                                    <div class="text-xs text-gray-500">jane.smith@sanjulian.gov.ph</div>
                                </div>
                            </td>
                            <td>EMP-2023-003</td>
                            <td>Finance Department</td>
                            <td>Accountant</td>
                            <td>Mar 5, 2020</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="btn btn-xs btn-ghost" title="View Details">
                                        <i class="fi-rr-eye text-blue-500"></i>
                                    </button>
                                    <button class="btn btn-xs btn-ghost" title="Edit">
                                        <i class="fi-rr-edit text-green-500"></i>
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
    
    <!-- Add Employee Modal -->
    <div class="modal" id="addEmployeeModal">
        <div class="modal-box max-w-3xl">
            <h3 class="font-bold text-lg">Add New Employee</h3>
            <button class="btn btn-sm btn-circle absolute right-2 top-2" onclick="closeAddEmployeeModal()">✕</button>
            
            <form class="py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Personal Information -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">First Name</span>
                        </label>
                        <input type="text" class="input input-bordered" placeholder="First Name" required />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Last Name</span>
                        </label>
                        <input type="text" class="input input-bordered" placeholder="Last Name" required />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Email Address</span>
                        </label>
                        <input type="email" class="input input-bordered" placeholder="Email Address" required />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Contact Number</span>
                        </label>
                        <input type="text" class="input input-bordered" placeholder="Contact Number" required />
                    </div>
                    
                    <!-- Employment Information -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Department</span>
                        </label>
                        <select class="select select-bordered">
                            <option disabled selected>Select Department</option>
                            <option>IT Department</option>
                            <option>HR Department</option>
                            <option>Finance Department</option>
                            <option>Operations</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Position</span>
                        </label>
                        <input type="text" class="input input-bordered" placeholder="Position" required />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Date Hired</span>
                        </label>
                        <input type="date" class="input input-bordered" required />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Salary Grade</span>
                        </label>
                        <select class="select select-bordered">
                            <option disabled selected>Select Salary Grade</option>
                            <option>SG-10</option>
                            <option>SG-11</option>
                            <option>SG-12</option>
                            <option>SG-13</option>
                            <option>SG-14</option>
                            <option>SG-15</option>
                        </select>
                    </div>
                </div>
                
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeAddEmployeeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Employee</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openAddEmployeeModal() {
            document.getElementById('addEmployeeModal').classList.add('modal-open');
        }
        
        function closeAddEmployeeModal() {
            document.getElementById('addEmployeeModal').classList.remove('modal-open');
        }
    </script>
</x-layouts.layout>

