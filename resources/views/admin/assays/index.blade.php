<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Assays</h1>
        </div>
    </x-slot>

    <div class="py-12">
        @if(count($assays) === 0)
            <div class="text-left">
                <x-heroicon-o-cpu-chip class="h-12 w-12 text-gray-400"/>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No assays</h3>
                <p class="mt-1 text-sm text-gray-500">
                    There are no assays yet. You can create an assay by importing an rdml file or create one using the
                    form.
                </p>
                <div class="mt-6 space-x-4 flex">
                    @can('import-rdml')
                        <livewire:create-experiment-form/>
                    @endcan
                    @can('create-assay')
                        <x-primary-button as="a" href="{{ url('/assays/create') }}">Use form</x-primary-button>
                    @endcan
                </div>
            </div>
        @else
            @can('create-assay')
                <div class="flex justify-end">
                    <div>
                        <x-primary-button as="a" href="{{ url('/assays/create') }}">Create assay</x-primary-button>
                    </div>
                </div>
            @endcan
            <div class="bg-white shadow overflow-hidden sm:rounded-md @can('create-assay') mt-8 @endcan">
                <ul class="divide-y divide-gray-200">
                    @foreach($assays as $assay)
                        <livewire:assay-row :assay="$assay"/>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-app-layout>
