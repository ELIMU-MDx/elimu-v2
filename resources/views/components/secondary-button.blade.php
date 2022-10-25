<{{$as}} {{$attributes->merge([
    'class' => 'w-full flex items-center justify-center px-4 py-2 border border-indigo-600 text-base font-medium rounded-md text-indigo-600 cursor-pointer hover:bg-indigo-100',
    'tabindex' => '0'
    ])}}>
{{$slot}}
</{{$as}}>
