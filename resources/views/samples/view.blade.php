<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Sample {{$sample->identifier}}</h1>
        </div>
    </x-slot>

    <div class="py-12">
        <livewire:show-sample :sample="$sample"/>
    </div>
</x-app-layout>
