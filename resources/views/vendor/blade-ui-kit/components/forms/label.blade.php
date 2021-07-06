<label for="{{ $for }}" {{ $attributes->merge(['class' => 'font-semibold']) }}>
    {{ $slot ?? $fallback }}
</label>
