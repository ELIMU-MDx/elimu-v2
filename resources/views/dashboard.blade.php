<x-app-layout :links="[
    [
        'href' => route('dashboard'),
        'label' => 'Dashboard',
        'icon' => 'heroicon-o-home',
        'active' => true,
    ],
    [
        'href' => route('dashboard'),
        'label' => 'Experiments',
        'icon' => 'heroicon-o-beaker',
    ],
    [
        'href' => route('dashboard'),
        'label' => 'Assays',
        'icon' => 'heroicon-o-chip',
    ],
    [
        'href' => route('dashboard'),
        'label' => 'Quality Control',
        'icon' => 'heroicon-o-clipboard',
    ],
]">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        </div>
    </div>
</x-app-layout>
