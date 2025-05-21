<div class="card bg-white shadow-md h-full">
    <div class="card-body flex flex-col">
        <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
            <i class="{{ $icon }} text-blue-500 mr-2"></i>
            {{ $title }}
        </h2>
        
        {{ $slot }}
    </div>
</div>