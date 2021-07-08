@error($field, $bag)
    <div {{ $attributes->merge(['class' => 'mt-2 text-red-800']) }}>
        @if ($slot->isEmpty())
            {{ $message }}
        @else
            {{ $slot }}
        @endif
    </div>
@enderror
