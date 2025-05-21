<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        'department' => 'navigation.department_admin',
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
                <a href="{{ route('logout.get') }}" 
                   onclick="if(confirm('Are you sure you want to logout?')) { return true; } else { return false; }" 
                   class="flex items-center px-6 py-3 text-gray-600 font-medium hover:bg-blue-50 transition-colors duration-200 w-full text-left">
                    <i class="fas fa-sign-out-alt mr-3 text-lg"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>

        <!-- Scrollable main content -->
        <div class="flex-grow overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">{{ $header ?? ($title ?? 'Dashboard') }}</h1>

                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="text-gray-600 hover:text-blue-500">
                                <i class="fi-rr-bell text-xl"></i>
                                <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                            </button>
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
</body>

</html>
