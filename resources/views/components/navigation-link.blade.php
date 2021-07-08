@props(['active' => false])

<a {{$attributes->merge([
    'class' => 'group flex items-center px-2 py-2 text-base font-medium rounded-md '.
        ($active
        ? 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
        : 'bg-gray-100 text-gray-900')
    ]) }}>
    {{$slot}}
</a>
