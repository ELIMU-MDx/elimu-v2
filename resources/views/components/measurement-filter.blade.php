@props(['label', 'field', 'options', 'show'])
<div class="pt-4 pb-4" x-data="{show: @json($show)}">
    <fieldset class="min-w-full">
        <legend class="w-full px-2">
            <button type="button"
                    @click="show = !show"
                    class="flex w-full items-center justify-between p-2 text-gray-400 cursor-pointer hover:text-gray-500"
                    aria-controls="filter-section-0" :aria-expanded="show">
                <span class="text-sm font-medium text-gray-900">{{$label}}</span>
                <span class="ml-6 flex h-7 items-center">
                    <svg class="h-5 w-5" :class="{'-rotate-180': show, 'rotate-0': !show}"
                         xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd"/>
                    </svg>
                </span>
            </button>
        </legend>
        <div class="px-4 pt-4 pb-2 max-w-full" id="filter-section-0" x-show="show">
            <div class="space-y-6">
                @foreach($options as $key => $option)
                    <div class="flex items-center max-w-full">
                        <input id="{{$field}}-{{$key}}" wire:model="{{$field}}" value="{{$option}}" type="checkbox"
                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="{{$field}}-{{$key}}" class="ml-3 text-sm cursor-pointer text-gray-500 flex-1 block break-all">{{$option}}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </fieldset>
</div>
