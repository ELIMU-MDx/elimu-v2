<nav {{$attributes}}>
    <div class="mb-8 pb-2 border-b border-gray-200">
        @if($responsive)
            <div class="block px-4 py-2 text-xs text-gray-400">
                {{ __('Manage Team') }}
            </div>

            <div class="border-b border-gray"></div>
        @else
            <x-jet-dropdown align="right" width="60">
                <x-slot name="trigger">
                    <x-admin-navigation-link as="button" type="button" class="w-full"
                                             icon="heroicon-o-academic-cap">
                        {{ Auth::user()->currentStudy->identifier }}
                        <svg class="ml-auto -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </x-admin-navigation-link>
                </x-slot>

                <x-slot name="content">
                    <div class="w-60">
                        <!-- Team Management -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Study') }}
                        </div>

                        @can('manage-study', Auth::user()->currentStudy)
                            <x-jet-dropdown-link href="{{route('currentStudy.show')}}">
                                {{ __('Study setting') }}
                            </x-jet-dropdown-link>
                        @endcan

                        <x-jet-dropdown-link href="{{ route('studies.create') }}">
                            {{ __('Create New Study') }}
                        </x-jet-dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Study') }}
                        </div>

                        @foreach(Auth::user()->studies as $study)
                            <x-switchable-study :study="$study"/>
                        @endforeach
                    </div>
                </x-slot>
            </x-jet-dropdown>
        @endif
    </div>

    @foreach($links as $link)
        <x-admin-navigation-link href="{{$link['href']}}" icon="{{$link['icon']}}"
                                 active="{{$link['active']}}">
            {{$link['label']}}
        </x-admin-navigation-link>
    @endforeach
</nav>
