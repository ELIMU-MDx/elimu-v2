<div>
    @can('import-rdml')
        <x-primary-button type="button" wire:click="$set('openModal', true)">Upload rdml</x-primary-button>
        <form wire:submit.prevent="createExperiment">
            <x-jet-dialog-modal wire:model="openModal">
                <x-slot name="title">
                    {{ __('Upload rdml') }}
                </x-slot>

                <x-slot name="content">
                    <div class="space-y-6">
                        <div>
                            <x-jet-label for="rdml">Rdml</x-jet-label>
                            <x-file-button name="rdml"
                                           wire:model.defer="form.rdml"
                                           accept=".rdml"
                                           class="mt-1"
                                           multiple
                                           :file="$form['rdml']">
                                Choose rdml file
                            </x-file-button>
                            <x-jet-input-error for="form.rdml"/>
                        </div>
                        <div>
                            <x-jet-label for="assay_id">Choose or import an assay</x-jet-label>
                            <div class="relative flex items-center mt-1">
                                <select id="assay_id"
                                        name="assay_id"
                                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-l-md shadow-sm"
                                        wire:model.defer="form.assay_id"
                                >
                                    <option>Choose assay</option>
                                    @foreach($assays as $id => $assay)
                                        <option value="{{$id}}">{{Str::limit($assay, 20)}}</option>
                                    @endforeach
                                </select>
                                <x-file-button name="assay"
                                               id="assay-file-input"
                                               wire:model.defer="assayFile"
                                               accept=".xlsx"
                                               class="!rounded-l-none !border-l-transparent"
                                               :file="$assayFile"
                                               limit="10"
                                >
                                    Import new
                                </x-file-button>
                            </div>
                            <p class="text-sm mt-2 text-indigo-600 underline">
                                <a href="{{asset('templates/assay.xlsx')}}" target="_blank">Download template</a>
                            </p>
                            <x-jet-input-error for="form.assay_id"/>
                            <x-jet-input-error for="assayFile"/>
                        </div>
                        <div>
                            <x-jet-label for="meta">Link to ELN</x-jet-label>
                            <x-jet-input type="text" name="eln" wire:model.defer="form.eln" class="mt-1"/>
                            <x-jet-input-error for="form.eln"/>
                        </div>
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$set('openModal', false)">
                        {{ __('Cancel') }}
                    </x-jet-secondary-button>

                    <x-jet-button wire:loading.attr="disabled" class="ml-2" type="submit">
                        {{ __('Create experiment') }}
                    </x-jet-button>
                </x-slot>
            </x-jet-dialog-modal>
        </form>
    @endcan
</div>
