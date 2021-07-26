<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Experiments</h1>
        </div>
    </x-slot>

    <div class="py-12">
        @if($experiments->isEmpty())
            <div class="text-left">
                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No experiments</h3>
                <p class="mt-1 text-sm text-gray-500">
                    There are no experiments yet. Start analyzing rdml data by importing your first .rdml file.
                </p>
                <div class="mt-6">
                    <livewire:create-experiment-form/>
                </div>
            </div>
        @else

            <div class="flex justify-end">
                <livewire:create-experiment-form/>
            </div>

            <div class="bg-white shadow mt-8 overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($experiments as $experiment)
                        <livewire:experiment-row :experiment="$experiment"/>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-app-layout>
