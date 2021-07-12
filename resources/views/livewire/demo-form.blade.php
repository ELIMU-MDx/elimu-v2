@unless($result)
    <form class="space-y-6" wire:submit.prevent="analyze">
        <x-hero-button as="label" wire:loading.class="!bg-indigo-400" wire:target="rdml">
            <input type="file" class="hidden" wire:model="rdml">
            <span wire:loading wire:target="rdml">Uploading...</span>
            <span wire:loading.remove wire:target="rdml">
                @if($rdml)
                    {{$rdml->getClientOriginalName()}} ({{round($rdml->getSize() / 1024, 2)}} KB)
                @else
                    Choose .rdml File
                @endif
            </span>
        </x-hero-button>


        @if($assays && $rdml)
            <div>
                <x-label for="assay">Choose an assay or use a custom one</x-label>
                <select class="w-full rounded" name="assay" id="assay" wire:model="selectedAssay">
                    <option>Choose an assay</option>
                    <option value="new">Custom</option>
                    @foreach($assays as $assay)
                        <option value="{{$assay->id}}">{{$assay->name}}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if($selectedAssay)
            @foreach($targets as $key => $target)
                <div class="space-y-6" x-data="{quantify: @entangle('targets.'.$key.'.quantify').defer}">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <x-jet-section-title>
                            <x-slot name="title">
                                {{$target['target']}} | {{$target['fluor']}}
                            </x-slot>

                            <x-slot name="description">
                                Define parameters for the target {{$target['target']}}
                            </x-slot>
                        </x-jet-section-title>

                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="space-y-6">
                                <div class="col-span-6 sm:col-span-4">
                                    <x-jet-label for="cutoff-{{$key}}" value="{{ __('Cutoff') }}"/>
                                    <x-input name="cutoff-{{$key}}" class="mt-1 block w-full"
                                             wire:model.defer="targets.{{$key}}.cutoff"/>
                                    <x-jet-input-error for="targets.{{$key}}.cutoff" class="mt-2"/>
                                </div>
                                <div class="col-span-6 sm:col-span-4">
                                    <x-jet-label for="cutoff_stddev-{{$key}}"
                                                 value="{{ __('Cutoff standard deviation') }}"/>
                                    <x-input name="cutoff_stddev-{{$key}}" class="mt-1 block w-full"
                                             wire:model.defer="targets.{{$key}}.cutoff_stddev"/>
                                    <x-jet-input-error for="targets.{{$key}}.cutoff_stddev" class="mt-2"/>
                                </div>
                                <div class="col-span-6 sm:col-span-4">
                                    <x-jet-label for="quantify-{{$key}}" value="{{ __('Quantify') }}"/>
                                    <label class="flex items-center mt-1">
                                        <x-checkbox name="quantify-{{$key}}" value="1" x-model="quantify"/>
                                        <span class="ml-4">Yes</span>
                                    </label>
                                    <x-jet-input-error for="targets.{{$key}}.quantify" class="mt-2"/>
                                </div>
                                <div class="col-span-6 sm:col-span-4" x-show="quantify">
                                    <x-jet-label for="slope-{{$key}}" value="{{ __('Slope') }}"/>
                                    <x-input name="slope-{{$key}}" class="mt-1 block w-full"
                                             wire:model.defer="targets.{{$key}}.slope"/>
                                    <x-jet-input-error for="targets.{{$key}}.slope" class="mt-2"/>
                                </div>
                                <div class="col-span-6 sm:col-span-4" x-show="quantify">
                                    <x-jet-label for="intercept-{{$key}}" value="{{ __('Intercept') }}"/>
                                    <x-input name="intercept-{{$key}}" class="mt-1 block w-full"
                                             wire:model.defer="targets.{{$key}}.intercept"/>
                                    <x-jet-input-error for="targets.{{$key}}.intercept" class="mt-2"/>
                                </div>
                                <div class="col-span-6 sm:col-span-4">
                                    <x-jet-label for="repetitions-{{$key}}"
                                                 value="{{ __('Required repetitions') }}"/>
                                    <x-input name="repetitions-{{$key}}" type="number" min="1" value="1"
                                             class="mt-1 block w-full"
                                             wire:model.defer="targets.{{$key}}.repetitions"/>
                                    <x-jet-input-error for="targets.{{$key}}.repetitions" class="mt-2"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    @unless($loop->last)
                        <x-jet-section-border/>
                    @endunless
                </div>
            @endforeach
            <x-hero-button type="submit">
                Analyze
            </x-hero-button>
        @endif
    </form>
@else
    <div>
        <ul class="space-y-6 mt-6">
            @foreach($result as $sample)
                <li>
                    <h3 class="font-extrabold text-xl">{{ $sample->id }}</h3>

                    <div class="mt-8">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Target
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Cq
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Quantify
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Qualify
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Standard Deviation
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Replications
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Errors</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($sample->targets as $key => $target)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{$target->id}}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{$target->cq}}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{$target->qualification}}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{$target->quantification}}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{$target->standardDeviation}}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{$target->replications}}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                                    @if($target->errors)
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="h-6 w-6 cursor-pointer"
                                                             fill="none"
                                                             x-data="{tooltip: '<ul class=\'list-disc\'>@foreach($target->errors as $error)<li>{{$error}}</li>@endforeach</ul>' }"
                                                             x-tooltip.html="tooltip"
                                                             viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <ul class="sr-only">
                                                            @foreach($target->errors as $error)
                                                                <li>{{$error}}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endunless
