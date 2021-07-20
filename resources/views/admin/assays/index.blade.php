<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Assays</h1>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="flex justify-end mb-8">
            <div>
                <x-primary-button as="a" href="{{ url('/assays/create') }}">Create assay</x-primary-button>
            </div>
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($assays as $assay)
                    <li>
                        <a href="{{ route('edit-assay', $assay->id) }}" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        {{$assay->name}}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100">
                                            {{$assay->created_by}}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="space-y-2 sm:flex sm:space-y-0 sm:space-x-6">
                                        @if($assay->sample_type)
                                            <p class="flex items-center text-sm text-gray-500">
                                                <x-heroicon-o-beaker class="flex-shrink-0 mr-2 h-5 w-5 text-gray-400"/>
                                                {{$assay->sample_type}}
                                            </p>
                                        @endif

                                        @if($assay->study)
                                            <p class="flex items-center text-sm text-gray-500">
                                                <x-heroicon-o-academic-cap
                                                        class="flex-shrink-0 mr-2 h-5 w-5 text-gray-400"/>
                                                {{$assay->study}}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <!-- Heroicon name: solid/calendar -->
                                        <p>
                                            Created at
                                            <time datetime="2020-01-07">January 7, 2020</time>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
