<{{$as}} {{$attributes->merge([
    'class' => 'w-full flex items-center justify-center px-4 py-2 text-sm border border-indigo-600 font-medium rounded-md text-indigo-600 cursor-pointer hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
    'tabindex' => '0'
    ])}}>
{{$slot}}
</{{$as}}>
