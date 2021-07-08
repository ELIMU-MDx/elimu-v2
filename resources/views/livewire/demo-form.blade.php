@unless($result)
    <form class="space-y-6" wire:submit.prevent="analyze">
        <x-primary-button as="label" wire:loading.class="!bg-indigo-400" wire:target="rdml">
            <input type="file" class="hidden" wire:model="rdml">
            <span wire:loading wire:target="rdml">Uploading...</span>
            <span wire:loading.remove wire:target="rdml">
                @if($rdml)
                    {{$rdml->getClientOriginalName()}} ({{round($rdml->getSize() / 1024, 2)}} KB)
                @else
                    Choose .rdml File
                @endif
            </span>
        </x-primary-button>

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
                    <h3 class="font-bold text-2xl bg-gray-50 p-4 -mx-4 rounded">{{$target['target']}}
                        | {{$target['fluor']}}</h3>
                    <div class="grid md:grid-cols-4 md:items-center">
                        <div class="col-span-2">
                            <x-label for="cutoff-{{$key}}">Cutoff</x-label>
                        </div>
                        <div class="col-span-2">
                            <x-input name="cutoff-{{$key}}" wire:model.defer="targets.{{$key}}.cutoff"/>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-4 md:items-center">
                        <div class="col-span-2">
                            <x-label for="cutoff_stddev-{{$key}}">Cutoff Stddev</x-label>
                        </div>
                        <div class="col-span-2">
                            <x-input name="cutoff_stddev-{{$key}}" wire:model.defer="targets.{{$key}}.cutoff"/>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-4 md:items-center">
                        <div class="col-span-2">
                            <x-label for="quantify-{{$key}}">Quantify</x-label>
                        </div>
                        <div class="col-span-2">
                            <label class="flex items-center">
                                <x-checkbox name="quantify-{{$key}}" value="1" x-model="quantify"/>
                                <span class="ml-4">Yes</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-4 md:items-center" x-show="quantify">
                        <div class="col-span-2">
                            <x-label for="slope-{{$key}}">Slope</x-label>
                        </div>
                        <div class="col-span-2">
                            <x-input name="slope-{{$key}}" wire:model.defer="targets.{{$key}}.slope"/>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-4 md:items-center" x-show="quantify">
                        <div class="col-span-2">
                            <x-label for="intercep-{{$key}}">Intercept</x-label>
                        </div>
                        <div class="col-span-2">
                            <x-input name="intercept-{{$key}}" wire:model.defer="targets.{{$key}}.intercept"/>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-4 md:items-center">
                        <div class="col-span-2">
                            <x-label for="repetitions-{{$key}}">Required repetitions</x-label>
                        </div>
                        <div class="col-span-2">
                            <x-input name="repetitions-{{$key}}" type="number" min="1" value="1"
                                     wire:model.defer="targets.{{$key}}.repetitions"/>
                        </div>
                    </div>
                </div>
            @endforeach
            <x-primary-button type="submit">
                Analyze
            </x-primary-button>
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
