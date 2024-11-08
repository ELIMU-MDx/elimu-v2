<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Study settings</h1>
        </div>
    </x-slot>

    <div class="py-12">
        <livewire:study-form :study="$study"/>
        <x-section-border/>
        <livewire:study-member-manager :study="$study"/>
    </div>
</x-app-layout>
