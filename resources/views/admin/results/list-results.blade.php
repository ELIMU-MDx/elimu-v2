<div>
    @if($assays->isEmpty())
        <div class="text-left">
            <x-heroicon-o-chart-pie class="h-12 w-12 text-gray-400"/>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No results</h3>
            <p class="mt-1 text-sm text-gray-500">
                There are no results yet. Start analyzing rdml data by importing your first .rdml file.
            </p>
            <div class="mt-6">
                <livewire:create-experiment-form/>
            </div>
        </div>
    @else
        <div class="flex justify-end items-baseline space-x-4">
            <div>
                <a href="{{route('api.results.index', $currentAssayId)}}" class="text-indigo-600 underline text-sm">Api link</a>
            </div>
            <x-primary-button as="a" href="{{route('results.export', $currentAssayId)}}" target="_blank">Download
                Excel
            </x-primary-button>
        </div>

        <section class="mt-8">
            <div class="flex flex-col">
                <div class="shadow sm:rounded-lg overflow-hidden">
                    <div class="bg-white flex flex-wrap items-baseline px-3 py-3">
                        <div class="relative flex items-stretch focus-within:z-10">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <x-jet-input type="search" name="search" id="search" class="pl-9" wire:model="search"
                                         placeholder="Search by sample"/>
                        </div>
                        <div class="text-gray-500 text-sm mx-2">
                            {{ $this->samples->count() }} / {{$this->totalSamples}} Results
                        </div>
                        <div class="ml-auto space-x-4">
                            @if(in_array($resultFilter, ['positive', 'negative']))
                                <x-select name="target" id="target" class="mt-1" wire:model="targetFilter">
                                    <option value="all">All Targets</option>
                                    @foreach($this->currentTargets as $target)
                                        <option value="{{$target}}" checked="checked" wire:key="target-{{$target}}"
                                        >{{$target}}</option>
                                    @endforeach
                                </x-select>
                            @endif

                            <x-select name="result" id="result" class="mt-1" wire:model="resultFilter">
                                <option value="all">All</option>
                                <option value="valid">Valid</option>
                                <option value="invalid">Invalid</option>
                                <option value="positive">Positive</option>
                                <option value="negative">Negative</option>
                            </x-select>

                            <x-select name="assay" id="assay" class="mt-1" wire:model="currentAssayId">
                                @foreach($assays as $assay)
                                    <option value="{{$assay->id}}"
                                            wire:key="assay-{{$assay->id}}">{{$assay->name}}</option>
                                @endforeach
                            </x-select>
                        </div>
                    </div>

                    <div class="-my-2 overflow-x-auto">
                        <div class="py-2 align-middle inline-block min-w-full">
                            <div class="overflow-hidden border-b border-t border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Sample
                                        </th>
                                        @foreach($this->currentTargets as $target)
                                            <th scope="col"
                                                class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-l border-gray-300">
                                                {{ $target }} Cq
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wide">
                                                {{ $target }} Result
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ $target }} Repetitions
                                            </th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if ($this->samples->isNotEmpty())
                                        @foreach($this->samples as $sample)
                                            <livewire:result-row :even="$loop->even"
                                                                 :sample="$sample"
                                                                 :wire:key="$sample->id"
                                            />
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="px-3 py-4 whitespace-nowrap text-left"
                                                colspan="{{ $this->currentAssay->parameters->count() * 3 + 1 }}">No
                                                results found
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if ($this->samples->hasPages())
                        <div class="bg-white px-3 py-3">
                            {{$this->samples->links()}}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif
</div>
