<x-layouts.layout>
    <x-slot:title>HR Profile</x-slot:title>
    <x-slot:header>My Profile</x-slot:header>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Picture Card -->
        <div class="card bg-white shadow-md">
            <div class="card-body flex flex-col items-center">
                <h2 class="card-title text-xl font-bold text-gray-800 mb-6 self-start">
                    <i class="fi-rr-user text-blue-500 mr-2"></i>
                    Profile Picture
                </h2>
                
                <div class="avatar mb-6">
                    <div class="w-40 h-40 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2 bg-blue-500 flex items-center justify-center overflow-hidden" id="profileImageContainer">
                        <img src="{{ asset('images/profile-placeholder.jpg') }}" alt="Profile Picture" class="w-full h-full object-cover hidden" id="profilePreview">
                        <span class="text-4xl font-bold text-white flex items-center justify-center w-full h-full" id="initialsDisplay">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                    </div>
                </div>
                
                <div class="space-y-4 w-full">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Update Profile Picture</span>
                        </label>
                        <input type="file" class="file-input file-input-bordered w-full" id="profilePicture" accept="image/*" />
                    </div>
                    
                    <button class="btn btn-primary w-full">
                        <i class="fi-rr-upload mr-2"></i>
                        Upload New Picture
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Personal Information Card -->
        <div class="card bg-white shadow-md lg:col-span-2">
            <div class="card-body">
                <h2 class="card-title text-xl font-bold text-gray-800 mb-6">
                    <i class="fi-rr-info text-blue-500 mr-2"></i>
                    Personal Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Full Name</h3>
                            <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Employee ID</h3>
                            <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->employee_id }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Position</h3>
                            <p class="text-lg font-semibold text-gray-800">HR Manager</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Department</h3>
                            <p class="text-lg font-semibold text-gray-800">Human Resources</p>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Date Hired</h3>
                            <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->created_at->format('F d, Y') }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Email Address</h3>
                            <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->email }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Contact Number</h3>
                            <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->phone_number }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Settings Card -->
    <div class="card bg-white shadow-md mt-6">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-6">
                <i class="fi-rr-settings text-blue-500 mr-2"></i>
                Account Settings
            </h2>
            
            <form class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email Address</span>
                    </label>
                    <input type="email" class="input input-bordered" value="{{ Auth::user()->email }}" disabled />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Notification Preferences</span>
                    </label>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" class="checkbox checkbox-primary" />
                            <span>Email Notifications</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" class="checkbox checkbox-primary" />
                            <span>SMS Notifications</span>
                        </div>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Time Zone</span>
                    </label>
                    <select class="select select-bordered">
                        <option>Philippines (UTC+8)</option>
                        <option>UTC</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
</x-layouts.layout>
