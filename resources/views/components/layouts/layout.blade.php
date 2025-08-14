<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <!-- Custom Pagination Styles -->
    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-base-200">
    <div class="flex h-screen overflow-hidden">
        <!-- Fixed sidebar - not scrollable -->
        <div class="w-72 bg-white shadow-md flex flex-col h-screen">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="avatar">
                        <div class="w-12 rounded-full">
                            <img src="{{ asset('images/sj-logo.jpg') }}">
                        </div>
                    </div>

                    <div class="ml-4">
                        <h1 class="text-xl font-bold text-blue-500">SmartLeave</h1>
                        <h3 class="text-sm font-semibold text-gray-500">LGU San Julian, Eastern Samar</h3>
                    </div>
                </div>
            </div>

            <nav class="flex-grow py-4">
                @php
                    // Get user type from authenticated user
                    $userType = Auth::user()->user_type ?? 'employee';
                    
                    // Determine navigation config based on user type
                    $navConfig = match($userType) {
                        'hr' => 'navigation.hr_manager',
                        'department_admin' => 'navigation.department_admin',
                        'mayor' => 'navigation.mayor',
                        default => 'navigation.employee'
                    };
                @endphp

                @foreach (config($navConfig) as $item)
                    <x-sidebar-item label="{{ $item['label'] }}" icon="{{ $item['icon'] }}"
                        route="{{ $item['route'] }}" />
                @endforeach
            </nav>

            <!-- Logout button at bottom -->
            <div class="p-4 border-t border-gray-200">
                <button onclick="showLogoutModal()" 
                   class="flex items-center px-6 py-3 text-gray-600 font-medium hover:bg-blue-50 transition-colors duration-200 w-full text-left">
                    <i class="fas fa-sign-out-alt mr-3 text-lg"></i>
                    <span>Logout</span>
                </button>
            </div>

            <!-- Logout Confirmation Modal -->
            <div id="logoutModal" class="fixed inset-0 z-50 hidden">
                <div class="absolute inset-0 bg-black/50"></div>
                <div class="fixed inset-0 flex items-center justify-center">
                    <div class="bg-white rounded-lg shadow-xl p-6 w-96 transform transition-all">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                <i class="fas fa-sign-out-alt text-red-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Confirm Logout</h3>
                            <p class="text-sm text-gray-500 mb-6">Are you sure you want to log out of your account?</p>
                            <div class="flex justify-center space-x-3">
                                <button onclick="hideLogoutModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md transition-colors duration-200">
                                    Cancel
                                </button>
                                <a href="{{ route('logout.get') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scrollable main content -->
        <div class="flex-grow overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">{{ $header ?? ($title ?? 'Dashboard') }}</h1>

                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="notifBell" class="text-gray-600 hover:text-blue-500 focus:outline-none" type="button">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                            </button>
                            <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-[9999]" style="min-width: 320px; background: #fff !important;" onclick="event.stopPropagation();">
                                <div class="p-4 border-b border-gray-100 font-bold text-gray-700">Notifications</div>
                                <ul class="max-h-64 overflow-y-auto">
                                    @if($userType === 'employee')
                                        <li class="px-4 py-3 hover:bg-gray-50 flex items-start space-x-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm text-gray-800">Your leave request for <span class="font-semibold">Vacation Leave</span> (May 10-12, 2024) was <span class="text-green-600 font-semibold">approved</span> by HR.</div>
                                                <div class="text-xs text-gray-400 mt-1">2 hours ago</div>
                                            </div>
                                        </li>
                                        <li class="px-4 py-3 hover:bg-gray-50 flex items-start space-x-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <i class="fas fa-times-circle text-red-500 text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm text-gray-800">Your leave request for <span class="font-semibold">Sick Leave</span> (Apr 20-21, 2024) was <span class="text-red-600 font-semibold">rejected</span> by Department Admin.</div>
                                                <div class="text-xs text-gray-400 mt-1">1 day ago</div>
                                            </div>
                                        </li>
                                        <li class="px-4 py-3 hover:bg-gray-50 flex items-start space-x-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <i class="fas fa-hourglass-end text-yellow-500 text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm text-gray-800">Your leave request for <span class="font-semibold">Vacation Leave</span> (Mar 5, 2024) is <span class="text-yellow-600 font-semibold">pending</span> HR approval.</div>
                                                <div class="text-xs text-gray-400 mt-1">3 days ago</div>
                                            </div>
                                        </li>
                                    @else
                                        <li class="px-4 py-3 hover:bg-gray-50 flex items-start space-x-3">
                                            <div class="flex-shrink-0 mt-1 flex items-center space-x-1">
                                                <i class="fas fa-hourglass-half text-yellow-500 text-lg"></i>
                                                <i class="fi-rr-envelope text-blue-500 text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm text-gray-800">New leave request from <span class="font-semibold">Daniel Pombo</span> for <span class="font-semibold">Vacation Leave</span>.</div>
                                                <div class="text-xs text-gray-400 mt-1">2 minutes ago</div>
                                            </div>
                                        </li>
                                        <li class="px-4 py-3 hover:bg-gray-50 flex items-start space-x-3">
                                            <div class="flex-shrink-0 mt-1 flex items-center space-x-1">
                                                <i class="fas fa-hourglass-half text-yellow-500 text-lg"></i>
                                                <i class="fi-rr-envelope text-blue-500 text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm text-gray-800">New leave request from <span class="font-semibold">Jane Smith</span> for <span class="font-semibold">Sick Leave</span>.</div>
                                                <div class="text-xs text-gray-400 mt-1">10 minutes ago</div>
                                            </div>
                                        </li>
                                        <li class="px-4 py-3 hover:bg-gray-50 flex items-start space-x-3">
                                            <div class="flex-shrink-0 mt-1 flex items-center space-x-1">
                                                <i class="fas fa-hourglass-half text-yellow-500 text-lg"></i>
                                                <i class="fi-rr-envelope text-blue-500 text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm text-gray-800">New leave request from <span class="font-semibold">Robert Johnson</span> for <span class="font-semibold">Emergency Leave</span>.</div>
                                                <div class="text-xs text-gray-400 mt-1">30 minutes ago</div>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                                <div class="p-2 text-center border-t border-gray-100">
                                    <a href="#" class="text-blue-500 text-sm hover:underline">View all notifications</a>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end">
                            <span class="text-sm font-bold text-gray-600">
                                {{ Auth::user()->first_name }} {{ Auth::user()->middle_initial ? Auth::user()->middle_initial . '.' : '' }} {{ Auth::user()->last_name }}
                            </span>
                            <span class="text-xs font-medium text-gray-500">{{ ucfirst(Auth::user()->user_type ?? 'Employee') }}</span>
                        </div>

                        <div class="avatar avatar-placeholder">
                            <div class="bg-neutral text-neutral-content w-12 rounded-full">
                                <span class="text-xl">
                                    {{ strtoupper(substr(Auth::user()->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name ?? 's', 0, 1)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main content -->
                {{ $slot }}
            </div>
        </div>
    </div>
    <script>
        function showLogoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('logoutModal');
            if (event.target === modal) {
                hideLogoutModal();
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !document.getElementById('logoutModal').classList.contains('hidden')) {
                hideLogoutModal();
            }
        });

        // Notification dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            const bell = document.getElementById('notifBell');
            const dropdown = document.getElementById('notifDropdown');
            if (bell && dropdown) {
                bell.addEventListener('click', function(e) {
                    e.stopPropagation();
                    console.log('Bell clicked!');
                    dropdown.classList.toggle('hidden');
                });
                // Prevent closing when clicking inside the dropdown
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
                document.addEventListener('click', function() {
                    if (!dropdown.classList.contains('hidden')) {
                        dropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
