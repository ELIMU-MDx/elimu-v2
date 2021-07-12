<x-jet-form-section submit="saveStudy">
    <x-slot name="title">
        {{ __('Study Details') }}
    </x-slot>

    <x-slot name="description">
        Unique informations about the study
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="identifier" value="{{ __('Identifier') }}"/>
            <x-input name="identifier" class="mt-1 block w-full" wire:model.defer="study.identifier" autofocus/>
            <x-jet-input-error for="study.identifier" class="mt-2"/>
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Study Name') }}"/>
            <x-input name="name" class="mt-1 block w-full" wire:model.defer="study.name"/>
            <x-jet-input-error for="study.name" class="mt-2"/>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>
        <x-jet-button>
            {{ $study->exists ? __('Save') : __('Create') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
