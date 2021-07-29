<input
    name="{{ $name }}"
    type="{{ $type }}"
    id="{{ $id }}"
    @if($value)value="{{ $value }}"@endif
    {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:bg-gray-50 disabled:text-gray-700']) }}
/>
