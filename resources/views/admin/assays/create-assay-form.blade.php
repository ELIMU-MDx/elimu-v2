<div>
    <form wire:submit="saveAssay" class="space-y-6">
        <x-action-section>
            <x-slot name="title">
                {{ __('General Information') }}
            </x-slot>

            <x-slot name="description">
                General information about the assay, helps other users to identify it
            </x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="name" value="{{ __('Name') }}"/>
                        <x-input name="name" class="mt-1 block w-full" wire:model="assay.name" required/>
                        <x-input-error for="assay.name" class="mt-2"/>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="sample_type" value="{{ __('Sample type') }}"/>
                        <x-input name="sample_type" class="mt-1 block w-full" wire:model="assay.sample_type"/>
                        <x-input-error for="assay.sample_type" class="mt-2"/>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="targets" value="{{ __('Targets') }}"/>
                        <x-input name="targets" class="mt-1 block w-full" wire:model.live.debounce.500ms="targets" required/>
                        <x-input-error for="targets" class="mt-2"/>
                        <x-input-help>Targets seperated by comma. The form is going to be extended after entering a
                            target
                        </x-input-help>
                    </div>
                </div>
            </x-slot>
        </x-action-section>


        @if($targets)
            @foreach($parameters as $key => $target)
                <div wire:key="{{$key}}">
                    <x-section-border/>
                    <div class="mt-10 sm:mt-0"
                         x-data="{quantify: @entangle('parameters.'.$key.'.quantify'), validationMethod: '{{$parameters[$key]->coefficient_of_variation_cutoff ? 'coefficient_of_variation' : 'standard_deviation'}}'}">
                        <x-action-section>
                            <x-slot name="title">
                                {{$target->target}}
                            </x-slot>

                            <x-slot name="description">
                                Define parameters for the target {{$target->target}}
                            </x-slot>

                            <x-slot name="content">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-label for="description-{{$key}}"
                                                     value="{{ __('Description') }}"/>
                                        <x-input name="description-{{$key}}" class="mt-1 block w-full"
                                                 wire:model="parameters.{{$key}}.description"/>
                                        <x-input-error for="parameters.{{$key}}.description" class="mt-2"/>
                                        <x-input-help>Is used in the report</x-input-help>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-label for="is-control-{{$key}}" value="{{ __('Is control target') }}"/>
                                        <label class="flex items-center mt-1">
                                            <x-checkbox name="is-control-{{$key}}"
                                                        wire:model="parameters.{{$key}}.is_control"/>
                                            <span class="ml-4">Yes</span>
                                        </label>
                                        <x-input-error for="parameters.{{$key}}.is_control" class="mt-2"/>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-label for="positive-control-{{$key}}"
                                                     value="{{ __('Positive control') }}"/>
                                        <x-input name="positive-control-{{$key}}" class="mt-1 block w-full"
                                                 wire:model="parameters.{{$key}}.positive_control"/>
                                        <x-input-error for="parameters.{{$key}}.positive_control" class="mt-2"/>
                                        <x-input-help>Either null for a NaN cq or a threshold which the control has to
                                            undercut. Leave empty if there should be no validation
                                        </x-input-help>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-label for="negative-control-{{$key}}"
                                                     value="{{ __('Negative control') }}"/>
                                        <x-input name="negative-control-{{$key}}" class="mt-1 block w-full"
                                                 wire:model="parameters.{{$key}}.negative_control"/>
                                        <x-input-error for="parameters.{{$key}}.negative_control" class="mt-2"/>
                                        <x-input-help>Either null for a NaN cq or a threshold which the control has to
                                            undercut. Leave empty if there should be no validation
                                        </x-input-help>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-label for="ntc-control-{{$key}}" value="{{ __('NTC control') }}"/>
                                        <x-input name="ntc-control-{{$key}}" class="mt-1 block w-full"
                                                 wire:model="parameters.{{$key}}.ntc_control"/>
                                        <x-input-error for="parameters.{{$key}}.ntc_control" class="mt-2"/>
                                        <x-input-help>Either null for a NaN cq or a threshold which the control has to
                                            undercut. Leave empty if there should be no validation
                                        </x-input-help>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-label for="cutoff-{{$key}}" value="{{ __('Cutoff') }}"/>
                                        <x-input name="cutoff-{{$key}}" class="mt-1 block w-full"
                                                 wire:model="parameters.{{$key}}.cutoff"/>
                                        <x-input-error for="parameters.{{$key}}.cutoff" class="mt-2"/>
                                    </div>

                                    <div class="col-span-6 sm:col-span-4">
                                        <x-label for="cutoff-{{$key}}" value="{{ __('Validation using') }}"/>
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
                                        <x-label for="coefficient_of_variation_cutoff-{{$key}}"
                                                     value="{{ __('Cutoff coefficient of variation') }}"/>
                                        <x-input name="coefficient_of_variation_cutoff-{{$key}}"
                                                 class="mt-1 block w-full"
                                                 wire:model="parameters.{{$key}}.coefficient_of_variation_cutoff"
                                        placeholder="0.23"/>
                                        <x-input-error for="parameters.{{$key}}.coefficient_of_variation_cutoff"
                                                           class="mt-2"/>
                                        <x-input-help>Value between 0 and 1. 0 = 0%, 1 = 100%</x-input-help>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4"
                                         x-show="validationMethod === 'standard_deviation'">
                                        <x-label for="cutoff_stddev-{{$key}}"
                                                     value="{{ __('Cutoff standard deviation') }}"/>
                                        <x-input name="cutoff_stddev-{{$key}}" class="mt-1 block w-full"
                                                 wire:model="parameters.{{$key}}.standard_deviation_cutoff"
                                        placeholder="2.0"/>
                                        <x-input-error for="parameters.{{$key}}.standard_deviation_cutoff"
                                                           class="mt-2"/>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-label for="quantify-{{$key}}" value="{{ __('Quantify') }}"/>
                                        <label class="flex items-center mt-1">
                                            <x-checkbox name="quantify-{{$key}}" value="1" x-model="quantify"/>
                                            <span class="ml-4">Yes</span>
                                        </label>
                                        <x-input-error for="parameters.{{$key}}.quantify" class="mt-2"/>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4" x-show="quantify">
                                        <x-label for="slope-{{$key}}" value="{{ __('Slope') }}"/>
                                        <x-input name="slope-{{$key}}" class="mt-1 block w-full"
                                                 wire:model="parameters.{{$key}}.slope"/>
                                        <x-input-error for="parameters.{{$key}}.slope" class="mt-2"/>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4" x-show="quantify">
                                        <x-label for="intercept-{{$key}}" value="{{ __('Intercept') }}"/>
                                        <x-input name="intercept-{{$key}}" class="mt-1 block w-full"
                                                 wire:model="parameters.{{$key}}.intercept"/>
                                        <x-input-error for="parameters.{{$key}}.intercept" class="mt-2"/>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-label for="repetitions-{{$key}}"
                                                     value="{{ __('Required repetitions') }}"/>
                                        <x-input name="repetitions-{{$key}}" type="number" min="1" value="1"
                                                 class="mt-1 block w-full"
                                                 wire:model="parameters.{{$key}}.required_repetitions"/>
                                        <x-input-error for="parameters.{{$key}}.required_repetitions" class="mt-2"/>
                                    </div>
                                </div>
                            </x-slot>
                        </x-action-section>
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
