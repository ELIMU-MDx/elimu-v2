<div {{$attributes->merge(['class' => 'grid grid-cols-1 items-center md:grid-cols-3'])}}>
    <div class="col-span-1">
        <label for="{{ $id }}">
            {{ $label }}
        </label>
    </div>
    <div class="col-span-2">
        <x-input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" />
    </div>
</div>
