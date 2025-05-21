<?php
    return [
        'employee' => [
            [
                'label' => 'Dashboard',
                'icon' => 'fas fa-home',
                'route' => 'employee.dashboard',
            ],
            [
                'label' => 'Request Leave',
                'icon' => 'fas fa-calendar-alt',
                'route' => 'employee.request.leave',
            ],
            [
                'label' => 'Leave History',
                'icon' => 'fas fa-calendar-check',
                'route' => 'employee.leave.history',
            ],
            [
                'label' => 'Profile',
                'icon' => 'fas fa-user',
                'route' => 'employee.profile',
            ],
            [
                'label' => 'Settings',
                'icon' => 'fas fa-cog',
                'route' => 'employee.settings',
            ],
        ],
        'hr_manager' => [
            [
                'label' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'route' => 'hr.dashboard',
            ],
            [
                'label' => 'Leave Requests',
                'icon' => 'fas fa-clock',
                'route' => 'leave.requests',
            ],
            [
                'label' => 'Leave Credits',
                'icon' => 'fas fa-coins',
                'route' => 'leave.credits',
            ],
            [
                'label' => 'Employees',
                'icon' => 'fas fa-users',
                'route' => 'hr.employees',
            ],
            [
                'label' => 'Profile',
                'icon' => 'fas fa-user',
                'route' => 'hr.profile',
            ],
            [
                'label' => 'Settings',
                'icon' => 'fas fa-cog',
                'route' => 'hr.settings',
            ],
        ],
        'department_admin' => [
            [
                'label' => 'Dashboard',
                'icon' => 'fas fa-home',
                'route' => 'department.dashboard',
            ],
            [
                'label' => 'Leave Requests',
                'icon' => 'fas fa-clock',
                'route' => 'department.leave.requests',
            ],
            [
                'label' => 'Profile',
                'icon' => 'fas fa-user',
                'route' => 'department.profile',
            ],
            [
                'label' => 'Settings',
                'icon' => 'fas fa-cog',
                'route' => 'department.settings',
            ],
        ]
    ]
?>
