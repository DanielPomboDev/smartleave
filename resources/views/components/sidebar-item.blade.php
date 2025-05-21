<div>
    <a 
        href="{{ route($route) }}" 
        class="flex items-center px-6 py-3 text-gray-600 font-medium hover:bg-blue-50 transition-colors duration-200 {{ Route::currentRouteName() === $route ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-500' : '' }}">
        <i class="{{ $icon }} mr-3 text-lg {{ Route::currentRouteName() === $route ? 'text-blue-600' : '' }}"></i>
        <span>{{ $label }}</span>
    </a>
</div>
