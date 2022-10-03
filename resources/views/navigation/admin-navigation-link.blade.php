<{{$as}} {{$attributes->merge(['class' => 'group flex items-center px-2 py-2 text-base font-medium rounded-md ' . ($active ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900')])}}>
<x-icon name="{{$icon}}"
        class="mr-3 shrink-0 h-6 w-6 {{$active ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500'}}"
/>
{{$slot}}
</{{$as}}>
