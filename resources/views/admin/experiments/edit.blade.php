<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Edit assay</h1>
        </div>
    </x-slot>
    <div class="py-12">
        <x-form method="PUT" class="space-y-6" action="{{route('experiments.update', $experiment)}}">
            <x-action-section submit="saveStudy">
                <x-slot name="title">
                    {{ __('Further informations') }}
                </x-slot>

                <x-slot name="description">
                    Link with further information for this experiment or a text where to find it
                </x-slot>

                <x-slot name="content">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="eln" value="{{ __('Eln') }}"/>
                            <x-input type="text" name="eln" class="mt-1 block w-full" id="eln"
                                         value="{{$experiment->eln}}"/>
                            <x-input-error for="eln" class="mt-2"/>
                        </div>
                    </div>
                </x-slot>
            </x-action-section>
            <div class="flex justify-end">
                <a href="{{route('experiments.index')}}"
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save
                </button>
            </div>
        </x-form>

    </div>
</x-app-layout>
