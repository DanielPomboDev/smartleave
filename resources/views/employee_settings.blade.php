<!DOCTYPE html>
<html lang="en" class="h-full">
<x-layouts.layout>
    <x-slot:title>Settings & Change Password</x-slot:title>
    <x-slot:header>Settings & Change Password</x-slot:header>

    <div class="min-h-[calc(100vh-200px)] flex items-center justify-center p-4">
        <div class="w-full max-w-4xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Settings Navigation Card -->
                <div class="card bg-white shadow-md">
                    <div class="card-body">
                        <h2 class="card-title text-xl font-bold text-gray-800 mb-6">
                            <i class="fi-rr-settings text-blue-500 mr-2"></i>
                            Settings
                        </h2>
                        
                        <ul class="menu bg-base-100 w-full p-0">
                            <li class="menu-title">
                                <span class="text-gray-500">Account</span>
                            </li>
                            <li>
                                <a href="#password" class="active">
                                    <i class="fi-rr-lock text-blue-500"></i>
                                    Change Password
                                </a>
                            </li>
                            <li>
                                <a href="#notifications">
                                    <i class="fi-rr-bell text-blue-500"></i>
                                    Notifications
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Settings Content -->
                <div class="card bg-white shadow-md lg:col-span-2">
                    <!-- Change Password Card -->
                    <div class="card-body" id="password">
                        <h2 class="card-title text-xl font-bold text-gray-800 mb-6">
                            <i class="fi-rr-lock text-blue-500 mr-2"></i>
                            Change Your Password
                        </h2>

                        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- New Password -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium">New Password</span>
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" id="newPassword" class="input input-bordered w-full pr-10" placeholder="Enter your new password" required>
                                    <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center" onclick="togglePasswordVisibility('newPassword')">
                                        <i class="fi-rr-eye text-gray-400"></i>
                                    </button>
                                </div>
                                <label class="label">
                                    <p class="mt-1 text-xs text-gray-500 leading-relaxed">
                                        Must be 8+ characters with letters, numbers, and symbols
                                    </p>
                                </label>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium">Confirm New Password</span>
                                </label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="confirmPassword" class="input input-bordered w-full pr-10" placeholder="Confirm your new password" required>
                                    <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center" onclick="togglePasswordVisibility('confirmPassword')">
                                        <i class="fi-rr-eye text-gray-400"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Password Strength Indicator -->
                            <div class="space-y-2 pt-2">
                                <div class="flex justify-between items-center">
                                    <span class="block text-sm font-medium text-gray-700">Password Strength</span>
                                    <span class="text-xs font-medium" id="strengthText">Enter a new password</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500 ease-out" id="passwordStrength" style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- Informational Alert -->
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mt-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fi-rr-info text-blue-500 text-lg"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            For security reasons, you'll be logged out after changing your password.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-3 pt-6">
                                <button 
                                    type="button" 
                                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                    onclick="window.history.back()"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit" 
                                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex items-center"
                                    id="submitButton"
                                >
                                    <i class="fi-rr-key mr-2"></i>
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Notification Settings Card -->
                    <div class="card-body hidden" id="notifications">
                        <h2 class="card-title text-xl font-bold text-gray-800 mb-6">
                            <i class="fi-rr-bell text-blue-500 mr-2"></i>
                            Notification Settings
                        </h2>
                        
                        <form action="{{ route('settings.notifications.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-6">
                                <!-- Email Notifications -->
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h3 class="font-semibold text-gray-800 mb-3">Email Notifications</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-700">Leave Requests</p>
                                                <p class="text-sm text-gray-500">Get notified when a new leave request is submitted</p>
                                            </div>
                                            <input type="checkbox" name="email_leave_requests" class="toggle toggle-primary" {{ Auth::user()->notificationPreferences->email_leave_requests ? 'checked' : '' }}>
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-700">Approvals</p>
                                                <p class="text-sm text-gray-500">Get notified when your leave request is approved</p>
                                            </div>
                                            <input type="checkbox" name="email_approvals" class="toggle toggle-primary" {{ Auth::user()->notificationPreferences->email_approvals ? 'checked' : '' }}>
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-700">Rejections</p>
                                                <p class="text-sm text-gray-500">Get notified when your leave request is rejected</p>
                                            </div>
                                            <input type="checkbox" name="email_rejections" class="toggle toggle-primary" {{ Auth::user()->notificationPreferences->email_rejections ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- In-App Notifications -->
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h3 class="font-semibold text-gray-800 mb-3">In-App Notifications</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-700">Leave Requests</p>
                                                <p class="text-sm text-gray-500">Show notifications for new leave requests</p>
                                            </div>
                                            <input type="checkbox" name="in_app_leave_requests" class="toggle toggle-primary" {{ Auth::user()->notificationPreferences->in_app_leave_requests ? 'checked' : '' }}>
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-700">Approvals</p>
                                                <p class="text-sm text-gray-500">Show notifications for approved leave requests</p>
                                            </div>
                                            <input type="checkbox" name="in_app_approvals" class="toggle toggle-primary" {{ Auth::user()->notificationPreferences->in_app_approvals ? 'checked' : '' }}>
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-700">Rejections</p>
                                                <p class="text-sm text-gray-500">Show notifications for rejected leave requests</p>
                                            </div>
                                            <input type="checkbox" name="in_app_rejections" class="toggle toggle-primary" {{ Auth::user()->notificationPreferences->in_app_rejections ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Push Notifications -->
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h3 class="font-semibold text-gray-800 mb-3">Push Notifications</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-700">Leave Requests</p>
                                                <p class="text-sm text-gray-500">Receive push notifications for new leave requests</p>
                                            </div>
                                            <input type="checkbox" name="push_leave_requests" class="toggle toggle-primary" {{ Auth::user()->notificationPreferences->push_leave_requests ? 'checked' : '' }} disabled>
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-700">Approvals</p>
                                                <p class="text-sm text-gray-500">Receive push notifications for approved leave requests</p>
                                            </div>
                                            <input type="checkbox" name="push_approvals" class="toggle toggle-primary" {{ Auth::user()->notificationPreferences->push_approvals ? 'checked' : '' }} disabled>
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-700">Rejections</p>
                                                <p class="text-sm text-gray-500">Receive push notifications for rejected leave requests</p>
                                            </div>
                                            <input type="checkbox" name="push_rejections" class="toggle toggle-primary" {{ Auth::user()->notificationPreferences->push_rejections ? 'checked' : '' }} disabled>
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500 mt-2">
                                        <i class="fi-rr-info-circle mr-1"></i> Push notifications require a mobile app (coming soon)
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-3 pt-6">
                                <button 
                                    type="button" 
                                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                    onclick="window.history.back()"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit" 
                                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex items-center"
                                >
                                    <i class="fi-rr-save mr-2"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle between password and notification settings
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('ul.menu a');
            const sections = document.querySelectorAll('.card-body');
            
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all links
                    links.forEach(l => l.classList.remove('active'));
                    
                    // Add active class to clicked link
                    this.classList.add('active');
                    
                    // Hide all sections
                    sections.forEach(section => section.classList.add('hidden'));
                    
                    // Show the target section
                    const target = this.getAttribute('href').substring(1);
                    document.getElementById(target).classList.remove('hidden');
                });
            });
        });
        
        // Toggle password visibility with improved feedback
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fi-rr-eye');
                icon.classList.add('fi-rr-eye-crossed');
                icon.classList.add('text-blue-500');
                
                // Add visual feedback
                input.focus();
                input.parentElement.classList.add('ring-2', 'ring-blue-200');
                setTimeout(() => {
                    input.parentElement.classList.remove('ring-2', 'ring-blue-200');
                }, 1000);
            } else {
                input.type = 'password';
                icon.classList.remove('fi-rr-eye-crossed', 'text-blue-500');
                icon.classList.add('fi-rr-eye');
            }
        }
        
        // Enhanced password strength checker with better feedback
        const newPasswordInput = document.getElementById('newPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const strengthBar = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('strengthText');
        const submitButton = document.getElementById('submitButton');
        
        function updatePasswordStrength() {
            const password = newPasswordInput.value;
            let strength = 0;
            let messages = [];
            
            // Length check
            if (password.length >= 8) strength += 1;
            else messages.push('at least 8 characters');
            
            // Complexity checks
            if (/[A-Z]/.test(password)) strength += 1;
            else messages.push('one uppercase letter');
                
            if (/[a-z]/.test(password)) strength += 1;
            else messages.push('one lowercase letter');
                
            if (/[0-9]/.test(password)) strength += 1;
            else messages.push('one number');
                
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            else messages.push('one special character');
            
            // Calculate strength percentage (0-100)
            const strengthPercentage = Math.min(100, Math.round((strength / 5) * 100));
            
            // Update UI
            strengthBar.style.width = strengthPercentage + '%';
            
            // Determine strength level and set appropriate colors/messages
            let strengthLevel = '';
            if (password.length === 0) {
                strengthLevel = 'empty';
                strengthText.textContent = 'Enter a new password';
                strengthBar.className = 'h-full bg-gray-200';
            } else if (strengthPercentage < 40) {
                strengthLevel = 'weak';
                strengthText.textContent = 'Weak' + (messages.length ? ` - needs ${messages.join(', ')}` : '');
                strengthBar.className = 'h-full bg-red-500';
            } else if (strengthPercentage < 80) {
                strengthLevel = 'moderate';
                strengthText.textContent = 'Moderate' + (messages.length ? ` - add ${messages.slice(0, 2).join(' or ')}` : '');
                strengthBar.className = 'h-full bg-yellow-400';
            } else {
                strengthLevel = 'strong';
                strengthText.textContent = 'Strong password';
                strengthBar.className = 'h-full bg-green-500';
            }
            
            // Update password match status
            checkPasswordMatch();
            
            return strengthLevel;
        }
        
        function checkPasswordMatch() {
            if (!confirmPasswordInput) return;
            
            const password = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            // Reset all states
            confirmPasswordInput.classList.remove(
                'border-green-500', 'border-red-500', 'border-yellow-500',
                'ring-1', 'ring-green-200', 'ring-red-200', 'ring-yellow-200'
            );
            
            if (confirmPassword.length === 0) return;
            
            if (password === confirmPassword) {
                confirmPasswordInput.classList.add('border-green-500', 'ring-1', 'ring-green-200');
                return true;
            } else {
                confirmPasswordInput.classList.add('border-red-500', 'ring-1', 'ring-red-200');
                return false;
            }
        }
        
        // Event listeners
        if (newPasswordInput) {
            newPasswordInput.addEventListener('input', updatePasswordStrength);
            newPasswordInput.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'border-blue-400');
            });
            newPasswordInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'border-blue-400');
            });
        }
        
        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
            confirmPasswordInput.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'border-blue-400');
            });
            confirmPasswordInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'border-blue-400');
            });
        }
        
        // Form submission validation
        const form = document.querySelector('form[action="{{ route('settings.update') }}"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                const password = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const strength = updatePasswordStrength();
                
                if (strength === 'weak') {
                    e.preventDefault();
                    alert('Please choose a stronger password.');
                    newPasswordInput.focus();
                    return false;
                }
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match. Please check and try again.');
                    confirmPasswordInput.focus();
                    return false;
                }
                
                // Add loading state to button
                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fi-rr-spinner animate-spin mr-2"></i> Updating...';
                
                // Re-enable after 3 seconds in case submission fails
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }, 3000);
            });
        }
        
        // Initial check for pre-filled values
        document.addEventListener('DOMContentLoaded', function() {
            if (newPasswordInput && newPasswordInput.value) {
                updatePasswordStrength();
            }
        });
    </script>
</x-layouts.layout>
</html>