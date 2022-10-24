<div class="py-12">
    @if($this->experiments->isEmpty())
        <div class="text-left">
            <x-heroicon-o-beaker class="h-12 w-12 text-gray-400"/>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No experiments</h3>
            <p class="mt-1 text-sm text-gray-500">
                There are no experiments yet. Start analyzing rdml data by importing your first .rdml file.
            </p>
            <div class="mt-6">
                <livewire:create-experiment-form/>
            </div>
        </div>
    @else

        <div class="flex justify-between items-center">
            <div>
                <div class="relative inline-block text-left" x-data="{open: false, sort: @entangle('sort')}" wire:>
                    <div>
                        <button type="button"
                                @click="open = !open"
                                class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900"
                                id="menu-button" :aria-expanded="open" aria-haspopup="true">
                            Sort
                            <!-- Heroicon name: mini/chevron-down -->
                            <svg class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500 transition-all duration-200"
                                 :class="{
                                 'rotate-180': open
                                 }"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>

                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute left-0 z-10 mt-2 w-40 origin-top-left rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                        <div class="py-1" role="none">
                            <!--
                              Active: "bg-gray-100", Not Active: ""

                              Selected: "font-medium text-gray-900", Not Selected: "text-gray-500"
                            -->
                            <a href="#"
                               @click="open=false"
                               wire:click.prevent="updateSort('created_at')"
                               :class="sort === 'created_at' ? 'font-medium text-gray-900' : 'text-gray-500'"
                               class="hover:bg-gray-100 block px-4 py-2 text-sm" role="menuitem"
                               tabindex="-1" id="menu-item-0">Uploaded date</a>

                            <a
                                :class="sort === 'name' ? 'font-medium text-gray-900' : 'text-gray-500'"
                                @click="open=false"
                                wire:click.prevent="updateSort('name')"
                                href="#" class="hover:bg-gray-100 text-gray-500 block px-4 py-2 text-sm" role="menuitem" tabindex="-1"
                               id="menu-item-1">Name</a>
                        </div>
                    </div>
                </div>
            </div>

            <livewire:create-experiment-form/>
        </div>

        <div class="bg-white shadow mt-8 overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($this->experiments as $experiment)
                    <livewire:experiment-row :wire:key="$experiment->experimentId" :experiment="$experiment"/>
                @endforeach
            </ul>
        </div>
    @endif
</div>
