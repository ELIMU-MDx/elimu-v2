<div>
    @if($assays->isEmpty())
        <div class="text-left">
            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                 aria-hidden="true">
                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No projects</h3>
            <p class="mt-1 text-sm text-gray-500">
                There are no results yet. Start analyzing rdml data by importing your first .rdml file.
            </p>
            <div class="mt-6">
                <livewire:create-experiment-form/>
            </div>
        </div>
    @else
        <div class="flex flex-wrap items-end justify-between mb-8">
            <div>
                <x-jet-label for="assay">Assay</x-jet-label>
                <x-select name="assay" id="assay" class="mt-1" wire:model="currentAssayId">
                    @foreach($assays as $assayId => $assayName)
                        <option value="{{$assayId}}">{{$assayName}}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="ml-4">
                <livewire:create-experiment-form/>
            </div>
        </div>

        <section>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sample
                                    </th>
                                    @foreach($this->samples->first()->results as $result)
                                        <th scope="col"
                                            class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $result->target }} Cq
                                        </th>
                                        <th scope="col"
                                            class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wide">
                                            {{ $result->target }} Result
                                        </th>
                                        <th scope="col"
                                            class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $result->target }} Repetitions
                                        </th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($this->samples as $sampleKey => $sample)
                                    <livewire:experiment-result-row :even="$loop->even" :sample="$sample"/>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
