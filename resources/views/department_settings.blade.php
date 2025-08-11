<!DOCTYPE html>
<html lang="en" class="h-full">
<x-layouts.layout>
    <x-slot:title>Settings & Change Password</x-slot:title>
    <x-slot:header>Settings & Change Password</x-slot:header>

    <div class="min-h-[calc(100vh-200px)] flex items-center justify-center p-4">
        <div class="w-full max-w-xl">
            <div class="card bg-white shadow-md">
                <div class="card-body">
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
            </div>
        </div>
    </div>

    <script>
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
        
        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = [];
            
            // Length check
            if (password.length >= 8) {
                strength += 25;
            } else {
                feedback.push('At least 8 characters');
            }
            
            // Uppercase check
            if (/[A-Z]/.test(password)) {
                strength += 25;
            } else {
                feedback.push('Uppercase letter');
            }
            
            // Lowercase check
            if (/[a-z]/.test(password)) {
                strength += 25;
            } else {
                feedback.push('Lowercase letter');
            }
            
            // Number check
            if (/[0-9]/.test(password)) {
                strength += 25;
            } else {
                feedback.push('Number');
            }
            
            // Special character check
            if (/[^A-Za-z0-9]/.test(password)) {
                strength += 25;
            } else {
                feedback.push('Special character');
            }
            
            // Update UI
            strengthBar.style.width = `${Math.min(strength, 100)}%`;
            
            // Set color based on strength
            if (strength <= 25) {
                strengthBar.className = 'h-full rounded-full transition-all duration-500 ease-out bg-red-500';
                strengthText.textContent = 'Weak';
                strengthText.className = 'text-xs font-medium text-red-500';
            } else if (strength <= 50) {
                strengthBar.className = 'h-full rounded-full transition-all duration-500 ease-out bg-orange-500';
                strengthText.textContent = 'Fair';
                strengthText.className = 'text-xs font-medium text-orange-500';
            } else if (strength <= 75) {
                strengthBar.className = 'h-full rounded-full transition-all duration-500 ease-out bg-yellow-500';
                strengthText.textContent = 'Good';
                strengthText.className = 'text-xs font-medium text-yellow-500';
            } else {
                strengthBar.className = 'h-full rounded-full transition-all duration-500 ease-out bg-green-500';
                strengthText.textContent = 'Strong';
                strengthText.className = 'text-xs font-medium text-green-500';
            }
            
            return {
                strength,
                feedback
            };
        }
        
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            if (password) {
                const { strength, feedback } = checkPasswordStrength(password);
                if (strength < 100) {
                    strengthText.textContent = `Add ${feedback.join(', ')}`;
                }
            } else {
                strengthBar.style.width = '0%';
                strengthText.textContent = 'Enter a new password';
                strengthText.className = 'text-xs font-medium text-gray-500';
            }
        });
        
        confirmPasswordInput.addEventListener('input', function() {
            const password = this.value;
            const newPassword = newPasswordInput.value;
            
            if (password && newPassword) {
                if (password === newPassword) {
                    this.setCustomValidity('');
                } else {
                    this.setCustomValidity('Passwords do not match');
                }
            } else {
                this.setCustomValidity('');
            }
        });
        
        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match');
                return;
            }
            
            const { strength } = checkPasswordStrength(password);
            if (strength < 75) {
                e.preventDefault();
                alert('Please choose a stronger password');
                return;
            }
        });
    </script>
</x-layouts.layout>
