<{{$as}} {{$attributes->merge([
    'class' => 'w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 cursor-pointer hover:bg-indigo-700 md:py-4 md:text-lg md:px-10',
    'tabindex' => '0'
    ])}}>
{{$slot}}
</{{$as}}>
