<x-layouts.layout>
    <x-slot:title>Employee Profile</x-slot:title>
    <x-slot:header>My Profile</x-slot:header>
    
    @guest
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Please login to view your profile.</span>
        </div>
    @else
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
                            <span class="text-4xl font-bold text-white flex items-center justify-center w-full h-full" id="initialsDisplay">{{ strtoupper(substr(Auth::user()->first_name ?? 'U', 0, 1) . substr(Auth::user()->last_name ?? 'S', 0, 1)) }}</span>
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
                                <p class="text-lg font-semibold text-gray-800">{{ (Auth::user()->first_name ?? '') . ' ' . (Auth::user()->middle_initial ?? '') . (Auth::user()->middle_initial ? '. ' : '') . (Auth::user()->last_name ?? 'Not available') }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Employee ID</h3>
                                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->employee_id ?? Auth::user()->user_id ?? 'Not available' }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Position</h3>
                                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->position ?? 'Not available' }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Office/Agency</h3>
                                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->department->name ?? 'Not assigned' }}</p>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Date Hired</h3>
                                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->start_date ? date('F d, Y', strtotime(Auth::user()->start_date)) : 'Not available' }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Salary</h3>
                                <p class="text-lg font-semibold text-gray-800">₱{{ number_format(Auth::user()->salary ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

    
    <script>
        // Profile picture upload functionality
        const profilePicture = document.getElementById('profilePicture');
        const profilePreview = document.getElementById('profilePreview');
        const initialsDisplay = document.getElementById('initialsDisplay');
        
        profilePicture.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePreview.src = e.target.result;
                    profilePreview.classList.remove('hidden');
                    initialsDisplay.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endguest
</x-layouts.layout>
