<div>
    @can('import-rdml')
        <x-primary-button type="button" wire:click="$set('openModal', true)">Upload rdml</x-primary-button>
        <form wire:submit="createExperiment">
            <x-dialog-modal wire:model.live="openModal">
                <x-slot name="title">
                    {{ __('Upload rdml') }}
                </x-slot>

                <x-slot name="content">
                    <div class="space-y-6">
                        <div>
                            <x-label for="rdml">Rdml</x-label>
                            <x-file-button name="rdml"
                                           wire:model="form.rdml"
                                           accept=".rdml"
                                           class="mt-1"
                                           multiple
                                           :file="$form['rdml']">
                                Choose rdml file
                            </x-file-button>
                            <x-input-error for="form.rdml"/>
                        </div>
                        <div>
                            <x-label for="assay_id">Choose or import an assay</x-label>
                            <div class="relative flex items-center mt-1">
                                <select id="assay_id"
                                        name="assay_id"
                                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-l-md shadow-sm"
                                        wire:model="form.assay_id"
                                >
                                    <option>Choose assay</option>
                                    @foreach($assays as $id => $assay)
                                        <option value="{{$id}}">{{Str::limit($assay, 20)}}</option>
                                    @endforeach
                                </select>
                                <x-file-button name="assay"
                                               id="assay-file-input"
                                               wire:model="assayFile"
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
                            <x-input-error for="form.assay_id"/>
                            <x-input-error for="assayFile"/>
                        </div>
                        <div>
                            <x-label for="meta">Link to ELN</x-label>
                            <x-input type="text" name="eln" wire:model="form.eln" class="mt-1"/>
                            <x-input-error for="form.eln"/>
                        </div>
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('openModal', false)">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-button wire:loading.attr="disabled" class="ml-2" type="submit">
                        {{ __('Create experiment') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>
        </form>
    @endcan
</div>
