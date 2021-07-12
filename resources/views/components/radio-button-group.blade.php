<label class="relative flex items-center border border-gray-300 border-t-transparent bg-white shadow-sm px-6 py-4 cursor-pointer
first:border-t-gray-300 first:rounded-t-lg last:rounded-b-lg hover:border-gray-600 focus-within:border-indigo-500">
    <input type="radio" {{$attributes}} class="sr-only peer" id="{{$id}}" aria-labelledby="{{$id}}-label"
           aria-describedby="{{$id}}-description">
    <div class="flex items-center flex-1">
        <div class="text-sm">
            <p id="{{$id}}-label" class="font-medium text-gray-900">
                {{$title}}
            </p>
            <div id="{{$id}}-description" class="text-gray-500">
                {{$description}}
            </div>
        </div>
    </div>
    <svg class="ml-2 h-5 w-5 text-green-400 invisible peer-checked:visible"
         aria-hidden="true" fill="none" stroke-linecap="round"
         stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
</label>
