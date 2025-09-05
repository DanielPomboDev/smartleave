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
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $filters['department'] == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Position Filter -->
                    <div class="form-control w-52">
                        <label class="label py-1">
                            <span class="label-text font-medium text-gray-700">Position</span>
                        </label>
                        <select id="position-filter" class="select select-bordered w-full border-gray-300 focus:border-blue-500">
                            <option value="all">All Positions</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos }}" {{ $filters['position'] == $pos ? 'selected' : '' }}>
                                    {{ $pos }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Search Employee -->
                    <div class="form-control w-52">
                        <label class="label py-1">
                            <span class="label-text font-medium text-gray-700">Search</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="search-input" value="{{ $filters['search'] }}" placeholder="Search..." class="input input-bordered border-gray-300 focus:border-blue-500 w-full pr-10">
                            <button type="button" id="search-button" class="absolute right-2 top-1/2 -translate-y-1/2">
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
                            <th class="text-gray-600">Position</th>
                            <th class="text-gray-600">Department</th>
                            <th class="text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-8 h-8">
                                        <span class="bg-blue-500 text-white text-xs font-bold flex items-center justify-center w-full h-full">
                                            {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->user_id }}</div>
                                </div>
                            </td>
                            <td>{{ $user->position }}</td>
                            <td>{{ $user->department->name }}</td>
                            <td>
                                <div class="flex space-x-2">
                                    <button class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md border-none transition-colors duration-200 flex items-center justify-center gap-1" onclick="openEditEmployeeModal('{{ $user->user_id }}')">
                                        <i class="fi-rr-edit"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-md border-none transition-colors duration-200 flex items-center justify-center gap-1">
                                        <i class="fi-rr-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-end mt-6">
                <div class="btn-group">
                    @if($users->onFirstPage())
                        <button class="btn btn-sm" disabled>«</button>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="btn btn-sm">«</a>
                    @endif

                    @for($i = 1; $i <= $users->lastPage(); $i++)
                        @if($i == $users->currentPage())
                            <button class="btn btn-sm btn-active">{{ $i }}</button>
                        @else
                            <a href="{{ $users->url($i) }}" class="btn btn-sm">{{ $i }}</a>
                        @endif
                    @endfor

                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="btn btn-sm">»</a>
                    @else
                        <button class="btn btn-sm" disabled>»</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Employee Modal -->
    <div class="modal" id="addEmployeeModal">
        <div class="modal-box max-w-3xl">
            <h3 class="font-bold text-lg">Add New Employee</h3>
            <button class="btn btn-sm btn-circle absolute right-2 top-2" onclick="closeAddEmployeeModal()">✕</button>
            
            <form action="{{ route('hr.employees.store') }}" method="POST" class="py-4 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Personal Information -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">User ID</span>
                        </label>
                        <input type="text" name="user_id" class="input input-bordered" placeholder="User ID" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">First Name</span>
                        </label>
                        <input type="text" name="first_name" class="input input-bordered" placeholder="First Name" required />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Last Name</span>
                        </label>
                        <input type="text" name="last_name" class="input input-bordered" placeholder="Last Name" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Middle Initial</span>
                        </label>
                        <input type="text" name="middle_initial" class="input input-bordered" placeholder="Middle Initial" maxlength="1" />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Position</span>
                        </label>
                        <input type="text" name="position" class="input input-bordered" placeholder="Position" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Department</span>
                        </label>
                        <select name="department_id" class="select select-bordered" required>
                            <option disabled selected>Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Email</span>
                        </label>
                        <input type="email" name="email" class="input input-bordered" placeholder="Email Address" />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Account Type</span>
                        </label>
                        <select name="user_type" class="select select-bordered" required>
                            <option disabled selected>Select Account Type</option>
                            <option value="employee">Employee</option>
                            <option value="hr">HR</option>
                            <option value="department_admin">Department Admin</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Date Hired</span>
                        </label>
                        <input type="date" name="start_date" class="input input-bordered" required />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Salary</span>
                        </label>
                        <input type="number" name="salary" class="input input-bordered" placeholder="Salary" required />
                    </div>
                </div>
                
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeAddEmployeeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Employee</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Employee Modal -->
    <div class="modal" id="editEmployeeModal">
        <div class="modal-box max-w-3xl">
            <h3 class="font-bold text-lg">Edit Employee</h3>
            <button class="btn btn-sm btn-circle absolute right-2 top-2" onclick="closeEditEmployeeModal()">✕</button>
            
            <form id="editEmployeeForm" method="POST" class="py-4 space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Personal Information -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">User ID</span>
                        </label>
                        <input type="text" name="user_id" id="edit_user_id" class="input input-bordered" placeholder="User ID" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">First Name</span>
                        </label>
                        <input type="text" name="first_name" id="edit_first_name" class="input input-bordered" placeholder="First Name" required />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Last Name</span>
                        </label>
                        <input type="text" name="last_name" id="edit_last_name" class="input input-bordered" placeholder="Last Name" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Middle Initial</span>
                        </label>
                        <input type="text" name="middle_initial" id="edit_middle_initial" class="input input-bordered" placeholder="Middle Initial" maxlength="1" />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Position</span>
                        </label>
                        <input type="text" name="position" id="edit_position" class="input input-bordered" placeholder="Position" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Department</span>
                        </label>
                        <select name="department_id" id="edit_department_id" class="select select-bordered" required>
                            <option disabled>Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Email</span>
                        </label>
                        <input type="email" name="email" id="edit_email" class="input input-bordered" placeholder="Email Address" />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Account Type</span>
                        </label>
                        <select name="user_type" id="edit_user_type" class="select select-bordered" required>
                            <option disabled>Select Account Type</option>
                            <option value="employee">Employee</option>
                            <option value="hr">HR</option>
                            <option value="department_admin">Department Admin</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Date Hired</span>
                        </label>
                        <input type="date" name="start_date" id="edit_start_date" class="input input-bordered" required />
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Salary</span>
                        </label>
                        <input type="number" name="salary" id="edit_salary" class="input input-bordered" placeholder="Salary" required />
                    </div>
                </div>
                
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeEditEmployeeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Employee</button>
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

        // Function to update URL with filter parameters
        function updateFilters() {
            const department = document.getElementById('department-filter').value;
            const position = document.getElementById('position-filter').value;
            const search = document.getElementById('search-input').value;

            // Build query string
            const params = new URLSearchParams();
            if (department !== 'all') params.append('department', department);
            if (position !== 'all') params.append('position', position);
            if (search) params.append('search', search);

            // Update URL and reload page
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        // Add event listeners for filters
        document.getElementById('department-filter').addEventListener('change', updateFilters);
        document.getElementById('position-filter').addEventListener('change', updateFilters);
        document.getElementById('search-button').addEventListener('click', updateFilters);
        document.getElementById('search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                updateFilters();
            }
        });

        // Edit Employee Modal Functions
        function openEditEmployeeModal(userId) {
            if (!userId) {
                console.error('No user ID provided');
                return;
            }

            // Show loading state
            const modal = document.getElementById('editEmployeeModal');
            modal.classList.add('modal-open');
            
            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Fetch user data
            fetch(`/hr/employees/${userId}/edit`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text || 'Network response was not ok');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                // Populate form fields
                document.getElementById('edit_user_id').value = data.user_id || '';
                document.getElementById('edit_first_name').value = data.first_name || '';
                document.getElementById('edit_last_name').value = data.last_name || '';
                document.getElementById('edit_middle_initial').value = data.middle_initial || '';
                document.getElementById('edit_email').value = data.email || '';
                document.getElementById('edit_department_id').value = data.department_id || '';
                document.getElementById('edit_position').value = data.position || '';
                document.getElementById('edit_user_type').value = data.user_type || '';
                document.getElementById('edit_start_date').value = data.start_date || '';
                document.getElementById('edit_salary').value = data.salary || '';

                // Update form action
                document.getElementById('editEmployeeForm').action = `/hr/employees/${userId}`;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading employee data: ' + error.message);
                closeEditEmployeeModal();
            });
        }
        
        function closeEditEmployeeModal() {
            document.getElementById('editEmployeeModal').classList.remove('modal-open');
        }
    </script>
</x-layouts.layout>
