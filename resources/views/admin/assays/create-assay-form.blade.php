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
                        <x-input-help>Targets seperated by comma. The form is going to be extended after entering a
                            target
                        </x-input-help>
                    </div>
                </div>
            </x-slot>
        </x-jet-action-section>


        @if($targets)
            @foreach($parameters as $key => $target)
                <div wire:key="{{$key}}">
                    <x-jet-section-border/>
                    <div class="mt-10 sm:mt-0"
                         x-data="{quantify: @entangle('parameters.'.$key.'.quantify').defer, validationMethod: '{{$parameters[$key]->coefficient_of_variation_cutoff ? 'coefficient_of_variation' : 'standard_deviation'}}'}">
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
                                        <x-jet-label for="positive-control-{{$key}}"
                                                     value="{{ __('Positive control') }}"/>
                                        <x-input name="positive-control-{{$key}}" class="mt-1 block w-full"
                                                 wire:model.defer="parameters.{{$key}}.positive_control"/>
                                        <x-jet-input-error for="parameters.{{$key}}.positive_control" class="mt-2"/>
                                        <x-input-help>Either null for a NaN cq or a threshold which the control has to
                                            undercut. Leave empty if there should be no validation
                                        </x-input-help>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-jet-label for="negative-control-{{$key}}"
                                                     value="{{ __('Negative control') }}"/>
                                        <x-input name="negative-control-{{$key}}" class="mt-1 block w-full"
                                                 wire:model.defer="parameters.{{$key}}.negative_control"/>
                                        <x-jet-input-error for="parameters.{{$key}}.negative_control" class="mt-2"/>
                                        <x-input-help>Either null for a NaN cq or a threshold which the control has to
                                            undercut. Leave empty if there should be no validation
                                        </x-input-help>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-jet-label for="ntc-control-{{$key}}" value="{{ __('NTC control') }}"/>
                                        <x-input name="ntc-control-{{$key}}" class="mt-1 block w-full"
                                                 wire:model.defer="parameters.{{$key}}.ntc_control"/>
                                        <x-jet-input-error for="parameters.{{$key}}.ntc_control" class="mt-2"/>
                                        <x-input-help>Either null for a NaN cq or a threshold which the control has to
                                            undercut. Leave empty if there should be no validation
                                        </x-input-help>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-jet-label for="cutoff-{{$key}}" value="{{ __('Cutoff') }}"/>
                                        <x-input name="cutoff-{{$key}}" class="mt-1 block w-full"
                                                 wire:model.defer="parameters.{{$key}}.cutoff"/>
                                        <x-jet-input-error for="parameters.{{$key}}.cutoff" class="mt-2"/>
                                    </div>

                                    <div class="col-span-6 sm:col-span-4">
                                        <x-jet-label for="cutoff-{{$key}}" value="{{ __('Validation using') }}"/>
                                        <span class="isolate inline-flex rounded-md shadow-sm mt-1">
                                            <button
                                                :class="{
                                                    'text-indigo-600 bg-indigo-100': validationMethod === 'coefficient_of_variation',
                                                    'text-gray-700 bg-white': validationMethod !== 'coefficient_of_variation'
                                                }"
                                                @click="validationMethod = 'coefficient_of_variation'"
                                                type="button"
                                                class="relative inline-flex items-center rounded-l-md border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                coefficient of variation
                                            </button>
                                            <button
                                                :class="{
                                                    'text-indigo-600 bg-indigo-100': validationMethod === 'standard_deviation',
                                                    'text-gray-700 bg-white': validationMethod !== 'standard_deviation'
                                                }"
                                                @click="validationMethod = 'standard_deviation'"
                                                type="button"
                                                class="relative -ml-px inline-flex items-center rounded-r-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                standard deviation
                                            </button>
                                        </span>
                                    </div>

                                    <div class="col-span-6 sm:col-span-4"
                                         x-show="validationMethod === 'coefficient_of_variation'">
                                        <x-jet-label for="coefficient_of_variation_cutoff-{{$key}}"
                                                     value="{{ __('Cutoff coefficient of variation') }}"/>
                                        <x-input name="coefficient_of_variation_cutoff-{{$key}}"
                                                 class="mt-1 block w-full"
                                                 wire:model.defer="parameters.{{$key}}.coefficient_of_variation_cutoff"
                                        placeholder="0.23"/>
                                        <x-jet-input-error for="parameters.{{$key}}.coefficient_of_variation_cutoff"
                                                           class="mt-2"/>
                                        <x-input-help>Value between 0 and 1. 0 = 0%, 1 = 100%</x-input-help>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4"
                                         x-show="validationMethod === 'standard_deviation'">
                                        <x-jet-label for="cutoff_stddev-{{$key}}"
                                                     value="{{ __('Cutoff standard deviation') }}"/>
                                        <x-input name="cutoff_stddev-{{$key}}" class="mt-1 block w-full"
                                                 wire:model.defer="parameters.{{$key}}.standard_deviation_cutoff"
                                        placeholder="2.0"/>
                                        <x-jet-input-error for="parameters.{{$key}}.standard_deviation_cutoff"
                                                           class="mt-2"/>
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
