<x-layouts.layout>
    <x-slot:title>HR Settings</x-slot:title>
    <x-slot:header>Account Settings</x-slot:header>

    <div class="card bg-white shadow-md">
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
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">New Password</span>
                        </label>
                        <div class="relative">
                            <input type="password" class="input input-bordered w-full pr-10" id="newPassword" placeholder="Enter your new password">
                            <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center" onclick="togglePasswordVisibility('newPassword')">
                                <i class="fi-rr-eye text-gray-400"></i>
                            </button>
                        </div>
                        <label class="label">
                            <span class="label-text-alt text-gray-500">Password must be at least 8 characters long and include a mix of letters, numbers, and symbols.</span>
                        </label>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Confirm New Password</span>
                        </label>
                        <div class="relative">
                            <input type="password" class="input input-bordered w-full pr-10" id="confirmPassword" placeholder="Confirm your new password">
                            <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center" onclick="togglePasswordVisibility('confirmPassword')">
                                <i class="fi-rr-eye text-gray-400"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="space-y-2">
                        <label class="label p-0">
                            <span class="label-text font-medium">Password Strength</span>
                        </label>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-red-500 h-2.5 rounded-full" style="width: 0%" id="passwordStrength"></div>
                        </div>
                        <p class="text-xs text-gray-500" id="strengthText">Enter a new password</p>
                    </div>
                    
                    <div class="alert alert-info bg-blue-50 border-blue-100 text-blue-700">
                        <i class="fi-rr-info"></i>
                        <span>For security reasons, you'll be logged out after changing your password.</span>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <button type="button" class="btn btn-outline">Cancel</button>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fi-rr-eye');
                icon.classList.add('fi-rr-eye-crossed');
            } else {
                input.type = 'password';
                icon.classList.remove('fi-rr-eye-crossed');
                icon.classList.add('fi-rr-eye');
            }
        }
        
        // Password strength checker
        document.getElementById('newPassword').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');
            
            // Calculate strength
            let strength = 0;
            
            if (password.length > 0) strength += 20;
            if (password.length >= 8) strength += 20;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^A-Za-z0-9]/.test(password)) strength += 20;
            
            // Update strength bar
            strengthBar.style.width = strength + '%';
            
            // Update color based on strength
            if (strength <= 40) {
                strengthBar.classList.remove('bg-yellow-500', 'bg-green-500');
                strengthBar.classList.add('bg-red-500');
                strengthText.textContent = 'Weak';
            } else if (strength <= 80) {
                strengthBar.classList.remove('bg-red-500', 'bg-green-500');
                strengthBar.classList.add('bg-yellow-500');
                strengthText.textContent = 'Moderate';
            } else {
                strengthBar.classList.remove('bg-red-500', 'bg-yellow-500');
                strengthBar.classList.add('bg-green-500');
                strengthText.textContent = 'Strong';
            }
            
            if (password.length === 0) {
                strengthText.textContent = 'Enter a new password';
            }
        });
        
        // Check if passwords match
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = this.value;
            
            if (confirmPassword.length > 0) {
                if (newPassword === confirmPassword) {
                    this.classList.remove('input-error');
                    this.classList.add('input-success');
                } else {
                    this.classList.remove('input-success');
                    this.classList.add('input-error');
                }
            } else {
                this.classList.remove('input-success', 'input-error');
            }
        });
    </script>
</x-layouts.layout>

