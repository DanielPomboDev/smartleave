@php
    $colorClasses = [
        'blue' => [
            'bg' => 'bg-blue-50',
            'icon-bg' => 'bg-blue-100',
            'text' => 'text-blue-600',
            'icon' => 'text-blue-500'
        ],
        'green' => [
            'bg' => 'bg-green-50',
            'icon-bg' => 'bg-green-100',
            'text' => 'text-green-600',
            'icon' => 'text-green-500'
        ],
        'red' => [
            'bg' => 'bg-red-50',
            'icon-bg' => 'bg-red-100',
            'text' => 'text-red-600',
            'icon' => 'text-red-500'
        ],
        'yellow' => [
            'bg' => 'bg-yellow-50',
            'icon-bg' => 'bg-yellow-100',
            'text' => 'text-yellow-600',
            'icon' => 'text-yellow-500'
        ],
        'purple' => [
            'bg' => 'bg-purple-50',
            'icon-bg' => 'bg-purple-100',
            'text' => 'text-purple-600',
            'icon' => 'text-purple-500'
        ]
    ];
    
    $colors = $colorClasses[$bgColor] ?? $colorClasses['blue'];
@endphp

<div class="{{ $colors['bg'] }} rounded-lg p-5 flex items-center justify-between h-full">
    <div>
        <p class="text-sm font-medium text-gray-500">{{ $type }}</p>
        <p class="text-4xl font-bold {{ $colors['text'] }} mt-2">{{ $balance }}</p>
    </div>
    <div class="{{ $colors['icon-bg'] }} p-4 rounded-full">
        <i class="{{ $icon }} text-3xl {{ $colors['icon'] }}"></i>
    </div>
</div>


