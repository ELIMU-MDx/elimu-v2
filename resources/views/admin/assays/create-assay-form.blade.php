<div>
    <form wire:submit.prevent="saveAssay" class="space-y-6">
        <x-jet-action-section>
            <x-slot name="title">
                {{ __('General Information') }}
            </x-slot>

            <x-slot name="description">
                General information about the assay, helps other users to identify it
            </x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="name" value="{{ __('Name') }}"/>
                        <x-input name="name" class="mt-1 block w-full" wire:model.defer="assay.name" required/>
                        <x-jet-input-error for="assay.name" class="mt-2"/>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="sample_type" value="{{ __('Sample type') }}"/>
                        <x-input name="sample_type" class="mt-1 block w-full" wire:model.defer="assay.sample_type"/>
                        <x-jet-input-error for="assay.sample_type" class="mt-2"/>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="targets" value="{{ __('Targets') }}"/>
                        <x-input name="targets" class="mt-1 block w-full" wire:model.debounce.500ms="targets" required/>
                        <x-jet-input-error for="targets" class="mt-2"/>
                        <x-input-help>Targets seperated by comma. The form is going to be extended after entering a target</x-input-help>
                    </div>
                </div>
            </x-slot>
        </x-jet-action-section>


        @if($targets)
            @foreach($parameters as $key => $target)
                <div wire:key="{{$key}}">
                    <x-jet-section-border/>
                    <div class="mt-10 sm:mt-0" x-data="{quantify: @entangle('parameters.'.$key.'.quantify').defer}">
                        <x-jet-action-section>
                            <x-slot name="title">
                                {{$target->target}}
                            </x-slot>

                            <x-slot name="description">
                                Define parameters for the target {{$target->target}}
                            </x-slot>

                            <x-slot name="content">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-jet-label for="positive-control-{{$key}}" value="{{ __('Positive control') }}"/>
                                        <x-input name="positive-control-{{$key}}" class="mt-1 block w-full"
                                                 wire:model.defer="parameters.{{$key}}.positive_control"/>
                                        <x-jet-input-error for="parameters.{{$key}}.positive_control" class="mt-2"/>
                                        <x-input-help>Either null for a NaN cq or a threshold which the control has to undercut. Leave empty if there should be no validation</x-input-help>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-jet-label for="negative-control-{{$key}}" value="{{ __('Negative control') }}"/>
                                        <x-input name="negative-control-{{$key}}" class="mt-1 block w-full"
                                                 wire:model.defer="parameters.{{$key}}.negative_control"/>
                                        <x-jet-input-error for="parameters.{{$key}}.negative_control" class="mt-2"/>
                                        <x-input-help>Either null for a NaN cq or a threshold which the control has to undercut. Leave empty if there should be no validation</x-input-help>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-jet-label for="ntc-control-{{$key}}" value="{{ __('NTC control') }}"/>
                                        <x-input name="ntc-control-{{$key}}" class="mt-1 block w-full"
                                                 wire:model.defer="parameters.{{$key}}.ntc_control"/>
                                        <x-jet-input-error for="parameters.{{$key}}.ntc_control" class="mt-2"/>
                                        <x-input-help>Either null for a NaN cq or a threshold which the control has to undercut. Leave empty if there should be no validation</x-input-help>
                                    </div>
                                    <div class="col-span-6 grid grid-cols-6 gap-6">
                                        <div class="col-span-6 sm:col-span-2">
                                            <x-jet-label for="cutoff-{{$key}}" value="{{ __('Cutoff') }}"/>
                                            <x-input name="cutoff-{{$key}}" class="mt-1 block w-full"
                                                     wire:model.defer="parameters.{{$key}}.cutoff"/>
                                            <x-jet-input-error for="parameters.{{$key}}.cutoff" class="mt-2"/>
                                        </div>
                                        <div class="col-span-6 sm:col-span-2">
                                            <x-jet-label for="cutoff_stddev-{{$key}}"
                                                         value="{{ __('Cutoff standard deviation') }}"/>
                                            <x-input name="cutoff_stddev-{{$key}}" class="mt-1 block w-full"
                                                     wire:model.defer="parameters.{{$key}}.standard_deviation_cutoff"/>
                                            <x-jet-input-error for="parameters.{{$key}}.standard_deviation_cutoff"
                                                               class="mt-2"/>
                                        </div>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-jet-label for="quantify-{{$key}}" value="{{ __('Quantify') }}"/>
                                        <label class="flex items-center mt-1">
                                            <x-checkbox name="quantify-{{$key}}" value="1" x-model="quantify"/>
                                            <span class="ml-4">Yes</span>
                                        </label>
                                        <x-jet-input-error for="parameters.{{$key}}.quantify" class="mt-2"/>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4" x-show="quantify">
                                        <x-jet-label for="slope-{{$key}}" value="{{ __('Slope') }}"/>
                                        <x-input name="slope-{{$key}}" class="mt-1 block w-full"
                                                 wire:model.defer="parameters.{{$key}}.slope"/>
                                        <x-jet-input-error for="parameters.{{$key}}.slope" class="mt-2"/>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4" x-show="quantify">
                                        <x-jet-label for="intercept-{{$key}}" value="{{ __('Intercept') }}"/>
                                        <x-input name="intercept-{{$key}}" class="mt-1 block w-full"
                                                 wire:model.defer="parameters.{{$key}}.intercept"/>
                                        <x-jet-input-error for="parameters.{{$key}}.intercept" class="mt-2"/>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-jet-label for="repetitions-{{$key}}"
                                                     value="{{ __('Required repetitions') }}"/>
                                        <x-input name="repetitions-{{$key}}" type="number" min="1" value="1"
                                                 class="mt-1 block w-full"
                                                 wire:model.defer="parameters.{{$key}}.required_repetitions"/>
                                        <x-jet-input-error for="parameters.{{$key}}.required_repetitions" class="mt-2"/>
                                    </div>
                                </div>
                            </x-slot>
                        </x-jet-action-section>
                    </div>
                </div>
            @endforeach
        @endif
        <div class="flex justify-end">
            <button type="button"
                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </button>
            <button type="submit"
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save
            </button>
        </div>
    </form>
</div>
