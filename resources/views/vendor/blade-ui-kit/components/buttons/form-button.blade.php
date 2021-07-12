<form method="POST" action="{{ $action }}">
    @csrf
    @method($method)

    <x-primary-button type="submit" {{ $attributes->merge() }}>
        {{ $slot }}
    </x-primary-button>
</form>
