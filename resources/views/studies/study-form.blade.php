<x-form-section submit="saveStudy">
    <x-slot name="title">
        {{ __('Study Details') }}
    </x-slot>

    <x-slot name="description">
        Unique informations about the study
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="identifier">{{ __('Identifier') }}</x-label>
            <x-input name="identifier" class="mt-1 block w-full" wire:model="study.identifier" autofocus/>
            <x-input-error for="study.identifier" class="mt-2"/>
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name">{{ __('Study Name') }}</x-label>
            <x-input name="name" class="mt-1 block w-full" wire:model="study.name"/>
            <x-input-error for="study.name" class="mt-2"/>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>
        <x-button>
            {{ $study->exists ? __('Save') : __('Create') }}
        </x-button>
    </x-slot>
</x-form-section>
