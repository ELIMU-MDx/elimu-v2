<label for="{{ $for }}" {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $slot ?? $fallback }}
</label>
