@props(['value'])

<{{$as ?? 'lable'}} {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
{{ $value ?? $slot }}
</{{$as ?? 'lable'}}>
