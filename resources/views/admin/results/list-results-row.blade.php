<tr class="{{$even ? 'bg-gray-100' : 'bg-white'}} relative z-0">
    <td class="px-3 py-4 whitespace-nowrap text-sm text-left font-semibold">
        {{$sample->identifier}}
    </td>
    @foreach($sample->results as $result)
        <td class="px-3 py-4 whitespace-nowrap text-sm text-center border-l border-gray-300">
            {{$result->cq ?? 'NaN'}}
        </td>

        <td class="px-3 py-4 whitespace-nowrap text-center">
            @if($result->resultErrors->isNotEmpty())
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5 cursor-pointer text-red-600 mx-auto"
                     viewBox="0 0 20 20"
                     fill="currentColor"
                     x-data="{}"
                     x-tooltip.html="`<ul class=\'list-disc\'>
                            @foreach($result->resultErrors as $error)
                             <li>{{$error}}</li>
                            @endforeach
                             </ul>`">
                    <path fill-rule="evenodd"
                          d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                          clip-rule="evenodd"/>
                </svg>
                <ul class="sr-only">
                    @foreach($result->resultErrors as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            @else
                <div class="text-sm text-gray-900">
                    @if($result->qualification === 'POSITIVE')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Positive
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Negative
                        </span>
                    @endif
                </div>
                @if($result->quantification)
                    <div class="text-xs text-gray-500 mt-2">{{$result->quantification}}</div>
                @endif
            @endif
        </td>
        <td class="px-3 py-4 whitespace-nowrap text-sm text-center" x-data="{show: false}">
            <p>{{$result->measurements->included()->count()}} / {{$result->measurements->count()}}</p>
            <button class="text-indigo-600 font-semibold rounded-lg text-xs mt-1 underline"
                    x-on:click="show = true" x-show="!show">Edit
            </button>
            <button class="text-indigo-600 font-semibold rounded-lg text-xs mt-1 underline"
                    x-on:click="show = false"
                    x-cloak
                    x-show="show">Close
            </button>
            <div>
                <ul class="flex -mx-2 divide-x divide-gray-200 grid-cols-4 justify-center items-center"
                    x-show="show" x-cloak>
                    @foreach($result->measurements as $measurement)
                        <li class="px-2">
                            <button wire:click="toggleExcluded({{$measurement->id}})"
                                    wire:loading.class.remove="text-indigo-600"
                                    wire:loading.class="text-gray-200 cursor-wait"
                                    wire:loading.attr="disabled"
                                    class="text-indigo-600 @if($measurement->excluded) line-through @else underline @endif">
                                {{ $measurement->cq ?? 'NaN'}}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </td>
    @endforeach
</tr>
