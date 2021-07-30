<div class="py-12 bg-indigo-600 text-white">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
        <h2 class="text-3xl font-extrabold tracking-tight">Live demo</h2>

        @if ($errors->any())
            <div class="bg-white p-4 rounded mt-4">
                <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>

                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mt-4 space-y-6 md:flex md:items-center md:space-x-6 md:space-y-0">
            <div>
                <x-file-button wire:model="rdml" accept=".rdml" name="rdml" :file="$rdml">Choose rdml file
                </x-file-button>
            </div>
            <div>
                <x-file-button wire:model="assay" accept=".xlsx" name="assay" :file="$assay">Choose assay file
                </x-file-button>
            </div>
            <div>
                <a href="{{asset('/templates/assay.xlsx')}}" target="_blank" class="text-white underline">Download assay
                    file template</a>
            </div>
        </div>

        @if(count($this->measurements) > 0)
            <section class="mt-8">
                <div class="flex flex-col">
                    <div class="shadow sm:rounded-lg overflow-hidden">
                        <div class="-my-2 overflow-x-auto text-gray-900">
                            <div class="py-2 align-middle inline-block min-w-full">
                                <div class="overflow-hidden border-b border-t border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Sample
                                            </th>
                                            @foreach($this->measurements->pluck('target')->unique()->sort() as $target)
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
                                        @foreach($this->measurements->sortBy('sample')->groupBy('sample') as $sample => $measurements)
                                            <livewire:demo-form-row
                                                                    :even="$loop->even"
                                                                    :sample="$sample"
                                                                    :measurements="$measurements"
                                                                    :assay-parameters="$this->assayParameters"
                                            />
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>
</div>
