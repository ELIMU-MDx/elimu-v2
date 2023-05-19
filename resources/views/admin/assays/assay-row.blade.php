<li>
    <div class="px-4 py-4 flex items-center sm:px-6">
        <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
            <div class="truncate space-y-2">
                <div class="flex text-sm">
                    <p class="font-medium text-indigo-600 truncate">
                        {{$assay->name}} (ID: {{$assay->id}})
                    </p>
                </div>
                @if($assay->sample_type)
                    <p class="flex items-center text-sm text-gray-500">
                        <x-heroicon-s-beaker
                                class="shrink-0 mr-2 h-5 w-5 text-gray-400"/>
                        {{$assay->sample_type}}
                    </p>
                @endif

                <ul class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-4">
                    @foreach($assay->parameters as $assayParameter)
                        <li class="shrink-0 flex items-center text-sm text-gray-500">
                            @if($assayParameter->positive_control)
                                <x-heroicon-s-plus-circle class="shrink-0 h-5 w-5 mr-1.5 text-green-400"
                                                          x-data="{}"
                                                          x-tooltip="'Has a positive control parameter'"
                                />
                                <span class="sr-only">{{$assayParameter->target}} has a positive
                                    control parameter</span>
                            @else
                                <x-heroicon-s-plus-circle class="shrink-0 h-5 w-5 mr-1.5 text-gray-400"
                                                          x-data="{}"
                                                          x-tooltip="'Has no positive control parameter'"
                                />
                                <span class="sr-only">{{$assayParameter->target}} has no
                                    positive control parameter</span>
                            @endif
                            @if($assayParameter->negative_control)
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="shrink-0 h-5 w-5 mr-1.5 text-green-400"
                                     viewBox="0 0 20 20" fill="currentColor"
                                     x-data="{tooltip: 'Has a negative control parameter'}"
                                     x-tooltip="tooltip">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span class="sr-only">{{$assayParameter->target}} has a negative
                                    control parameter</span>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="shrink-0 h-5 w-5 mr-1.5 text-gray-400" viewBox="0 0 20 20"
                                     fill="currentColor"
                                     x-data="{tooltip: 'Has no negative control parameter'}"
                                     x-tooltip="tooltip"
                                >
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span class="sr-only">{{$assayParameter->target}} has no
                                    negative control parameter</span>
                            @endif
                            @if($assayParameter->ntc_control)
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="shrink-0 h-5 w-5 mr-1.5 text-green-400"
                                     x-data="{tooltip: 'Has a ntc control parameter'}"
                                     x-tooltip="tooltip"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span class="sr-only">{{$assayParameter->target}} has a ntc
                                    control parameter</span>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="shrink-0 h-5 w-5 mr-1.5 text-gray-400" viewBox="0 0 20 20"
                                     fill="currentColor"
                                     x-data="{tooltip: 'Has no ntc control parameter'}"
                                     x-tooltip="tooltip"
                                >
                                    <path fill-rule="evenodd"
                                          d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span class="sr-only">{{$assayParameter->target}} has no ntc
                                    control parameter</span>
                            @endif
                            {{$assayParameter->target}}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="flex ml-5 shrink-0 space-x-2">
            <a href="{{route('assays.download', $assay)}}"
               target="_blank"
               class="text-gray-600 transition-colors hover:text-indigo-600">
                <x-heroicon-s-folder-arrow-down class="h-5 w-5" />
                <span class="sr-only">Download assay</span>
            </a>
            @can('edit-assay', $assay)
                <a href="{{route('assays.edit', $assay)}}"
                   class="text-gray-600 transition-colors hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 " viewBox="0 0 20 20"
                         fill="currentColor">
                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                        <path fill-rule="evenodd"
                              d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="sr-only">Edit assay</span>
                </a>
            @endcan
            @can('delete-assay', $assay)
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
                    <span class="sr-only">Delete assay</span>
                </button>
            @endcan
        </div>
    </div>

    <x-confirmation-modal wire:model="showDeleteConfirmationModal">
        <x-slot name="title">
            {{ __('Delete Assay') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this assay? Once an assay is deleted, all results, data and experiments connected to this assay will be permanently deleted.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('showDeleteConfirmationModal')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-2" wire:click="deleteAssay" wire:loading.attr="disabled">
                {{ __('Delete Assay') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</li>
