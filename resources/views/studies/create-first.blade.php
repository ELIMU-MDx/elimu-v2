<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Create your first study</h1>
            <p class="max-w-prose mt-2 text-gray-500">After the study has been created, you can start uploading rdml
                files,
                analyzing results and invite co-workers to your study.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <livewire:study-form/>
    </div>
</x-app-layout>
