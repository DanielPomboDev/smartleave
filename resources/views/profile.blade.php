<x-layouts.layout>
    <x-slot:title>My Profile</x-slot:title>
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
                        <span class="text-4xl font-bold text-white flex items-center justify-center w-full h-full" id="initialsDisplay">DP</span>
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
                            <p class="text-lg font-semibold text-gray-800">Daniel Pombo</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Employee ID</h3>
                            <p class="text-lg font-semibold text-gray-800">EMP-2023-001</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Position</h3>
                            <p class="text-lg font-semibold text-gray-800">Senior Administrative Officer</p>
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
                            <p class="text-lg font-semibold text-gray-800">January 15, 2020</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Salary Grade</h3>
                            <p class="text-lg font-semibold text-gray-800">SG-18</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Email Address</h3>
                            <p class="text-lg font-semibold text-gray-800">daniel.pombo@sanjulian.gov.ph</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Contact Number</h3>
                            <p class="text-lg font-semibold text-gray-800">+63 912 345 6789</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Get elements
        const profilePicture = document.getElementById('profilePicture');
        const profilePreview = document.getElementById('profilePreview');
        const initialsDisplay = document.getElementById('initialsDisplay');
        
        // Check if there's a profile picture already
        window.addEventListener('DOMContentLoaded', function() {
            // This is a placeholder check - in a real app, you'd check if the src is a real image
            // For demo purposes, we're hiding the image and showing initials
            profilePreview.classList.add('hidden');
            initialsDisplay.classList.remove('hidden');
        });
        
        // Preview uploaded profile picture
        profilePicture.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    profilePreview.src = event.target.result;
                    profilePreview.classList.remove('hidden');
                    initialsDisplay.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-layouts.layout>

