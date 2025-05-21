<div class="form-control">
    <label for="{{ $name }}" class="block text-sm font-medium mb-1">{{ $label }}</label>
    <input 
        type="{{ $type ?? 'text' }}" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        placeholder="{{ $placeholder }}" 
        class="input input-bordered w-full {{ $class ?? '' }}" 
        {{ $attributes }}
    >
</div>