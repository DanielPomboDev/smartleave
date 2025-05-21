<x-layouts.layout>
    <x-slot:title>Leave Credits</x-slot:title>
    <x-slot:header>Leave Credits</x-slot:header>
    
    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title text-xl font-bold text-gray-800">
                    <i class="fi-rr-coins text-blue-500 mr-2"></i>
                    Manage Leave Credits
                </h2>
                
                <button class="btn btn-primary" onclick="openImportModal()">
                    <i class="fi-rr-upload mr-2"></i>
                    Import Leave Credits
                </button>
            </div>
            
            <!-- Filter Controls - Single Row -->
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
                    
                    <!-- Search Employee -->
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
            </div>
            
            <!-- Leave Credits Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-gray-600">Employee</th>
                            <th class="text-gray-600">Department</th>
                            <th class="text-gray-600">Vacation Leave</th>
                            <th class="text-gray-600">Sick Leave</th>
                            <th class="text-gray-600">Used Credits</th>
                            <th class="text-gray-600">Total Balance</th>
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
                                    <div class="text-xs text-gray-500">Software Developer</div>
                                </div>
                            </td>
                            <td>IT Department</td>
                            <td>15 days</td>
                            <td>12 days</td>
                            <td>8 days</td>
                            <td>19 days</td>
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
                                    <div class="text-xs text-gray-500">Accountant</div>
                                </div>
                            </td>
                            <td>Finance Department</td>
                            <td>10 days</td>
                            <td>8 days</td>
                            <td>5 days</td>
                            <td>13 days</td>
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
                                    <div class="text-xs text-gray-500">HR Specialist</div>
                                </div>
                            </td>
                            <td>HR Department</td>
                            <td>18 days</td>
                            <td>15 days</td>
                            <td>12 days</td>
                            <td>21 days</td>
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
                                    <div class="text-xs text-gray-500">Operations Manager</div>
                                </div>
                            </td>
                            <td>Operations Department</td>
                            <td>20 days</td>
                            <td>15 days</td>
                            <td>10 days</td>
                            <td>25 days</td>
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
                                    <div class="text-xs text-gray-500">System Administrator</div>
                                </div>
                            </td>
                            <td>IT Department</td>
                            <td>12 days</td>
                            <td>10 days</td>
                            <td>6 days</td>
                            <td>16 days</td>
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
    
    <!-- Import Leave Credits Modal -->
    <div class="modal" id="importModal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Import Leave Credits</h3>
            <button class="btn btn-sm btn-circle absolute right-2 top-2" onclick="closeImportModal()">✕</button>
            
            <div class="py-4">
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Select File</span>
                    </label>
                    <input type="file" class="file-input file-input-bordered w-full" />
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Supported formats: .xlsx, .csv</span>
                    </label>
                </div>
                
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Leave Type</span>
                    </label>
                    <select class="select select-bordered w-full">
                        <option disabled selected>Select leave type</option>
                        <option>All Leave Types</option>
                        <option>Vacation Leave</option>
                        <option>Sick Leave</option>
                    </select>
                </div>
                
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Action</span>
                    </label>
                    <select class="select select-bordered w-full">
                        <option disabled selected>Select action</option>
                        <option>Replace existing credits</option>
                        <option>Add to existing credits</option>
                        <option>Subtract from existing credits</option>
                    </select>
                </div>
                
                <div class="mt-4">
                    <a href="#" class="text-sm text-blue-500 hover:underline flex items-center">
                        <i class="fi-rr-download mr-1"></i>
                        Download template
                    </a>
                </div>
                
                <div class="modal-action">
                    <button class="btn btn-outline" onclick="closeImportModal()">Cancel</button>
                    <button class="btn btn-primary">
                        <i class="fi-rr-check mr-2"></i>
                        Import Credits
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript for modals -->
    <script>
        function openImportModal() {
            document.getElementById('importModal').classList.add('modal-open');
        }
        
        function closeImportModal() {
            document.getElementById('importModal').classList.remove('modal-open');
        }
    </script>
</x-layouts.layout>













