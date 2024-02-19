<li>
    <div class="px-4 py-4 flex items-center sm:px-6">
        <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
            <div class="truncate space-y-2">
                <div class="flex text-sm">
                    <a href="{{route('experiments.download', $experiment->experimentId)}}"
                       target="_blank"
                       class="font-medium text-indigo-600 truncate">
                        {{$experiment->name}}
                    </a>
                    @if($experiment->importPending)
                        <span class="text-gray-600 ml-4 animate-pulse">importing adps...</span>
                    @endif
                </div>
                <ul class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-4">
                    @if($experiment->eln)
                        <li class="shrink-0 flex items-center text-sm text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                <path
                                    d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                            </svg>
                            @if(is_url($experiment->eln))
                                <a href="{{$experiment->eln}}" target="_blank" class="underline">Link to ELN</a>
                            @else
                                <p>{{$experiment->eln}}</p>
                            @endif
                        </li>
                    @endif
                    <li class="shrink-0 flex items-center text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <p>{{$experiment->numberOfSamples}} samples</p>
                    </li>
                    <li class="shrink-0 flex items-center text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13 7H7v6h6V7z"/>
                            <path fill-rule="evenodd"
                                  d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <p>{{$experiment->assay}}</p>
                    </li>
                    <li class="shrink-0 flex items-center text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                             class="shrink-0 mr-1.5 h-5 w-5 text-gray-400"

                             x-data="{}"
                             x-tooltip="'Instrument'"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                        </svg>

                        <p>{{$experiment->instrument}}</p>
                    </li>
                    @if($experiment->runDate)
                        <li class="shrink-0 flex items-center text-sm text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <p>
                                Run on
                                <time
                                    datetime="{{$experiment->runDate->format('Y-m-d H:i')}}">{{ $experiment->runDate->format('F j, Y H:i') }}</time>
                            </p>
                        </li>
                    @endif
                    <li class="shrink-0 flex items-center text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <p>
                            Uploaded on
                            <time
                                datetime="{{$experiment->uploadedDate->format('Y-m-d H:i')}}">{{ $experiment->uploadedDate->format('F j, Y H:i') }}</time>
                        </p>
                    </li>
                </ul>

                <ul class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-4">
                    @foreach($experiment->targets as $target)
                        <li class="shrink-0 flex items-center text-sm text-gray-500">
                            @empty($target->errors)
                                <svg class="shrink-0 mr-1.5 h-5 w-5 text-green-400"
                                     x-description="Heroicon name: solid/check-circle"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     fill="currentColor"
                                     aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="shrink-0 mr-1.5 h-5 w-5 cursor-pointer text-red-600"
                                     viewBox="0 0 20 20"
                                     fill="currentColor"
                                     x-data="{}"
                                     x-tooltip.html="`<ul class=\'list-disc\'>
                                                        @foreach($target->errors as $error)
                                             <li>{{$error}}</li>
                                                         @endforeach
                                             </ul>`"
                                >
                                    <path fill-rule="evenodd"
                                          d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                <ul class="sr-only">
                                    @foreach($target->errors as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            @endif
                            {{$target->name}}
                        </li>
                    @endforeach
                </ul>


                @if(!empty($experiment->targets->filter(fn($target) => $target->quantification)))
                    <ul class="flex flex-col space-y-2 md:flex-row md:flex-wrap md:space-y-0 md:space-x-4">
                        @foreach($experiment->targets->filter(fn($target) => $target->quantification) as $target)
                            <li class="shrink-0 flex items-center text-sm text-gray-500 border-2 border-gray-200 pr-2">
                                <div class="bg-gray-200 text-gray-600 font-bold mr-2 inline-block px-2 py-1">
                                    <p>{{$target->quantification->formula}}</p>
                                    @if($target->quantification->squareCorrelationCoefficient)
                                        <p>R<sup>2</sup> = {{$target->quantification->squareCorrelationCoefficient}}</p>
                                    @endif
                                    <p>E = {{$target->quantification->e}}</p>
                                </div>

                                {{$target->name}}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        <div class="flex ml-5 shrink-0 space-x-2">
            @can('edit-experiment', $experiment)
                <a href="{{route('experiments.edit', $experiment->experimentId)}}"
                   class="text-gray-600 transition-colors hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 " viewBox="0 0 20 20"
                         fill="currentColor">
                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                        <path fill-rule="evenodd"
                              d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                              clip-rule="evenodd"/>
                    </svg>
                </a>
            @endcan
            @can('delete-experiment', $experiment)
                <button class="text-red-600 hover:text-red-800"
                        type="button"
                        wire:click="$set('showDeleteConfirmationModal', true)"
                        wire:loading.attr="disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
            @endcan
        </div>
    </div>

    <x-confirmation-modal wire:model.live="showDeleteConfirmationModal">
        <x-slot name="title">
            {{ __('Delete Experiment') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this experiment? Once an experiment is deleted, all of its results and data will be permanently deleted.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('showDeleteConfirmationModal')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-2" wire:click="deleteExperiment" wire:loading.attr="disabled">
                {{ __('Delete Experiment') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</li>
