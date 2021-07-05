<x-guest-layout>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="relative bg-gray-50 overflow-hidden">
        <div class="hidden sm:block sm:absolute sm:inset-y-0 sm:h-full sm:w-full" aria-hidden="true">
            <div class="relative h-full max-w-7xl mx-auto">
                <svg class="absolute right-full transform translate-y-1/4 translate-x-1/4 lg:translate-x-1/2"
                     width="404" height="784" fill="none" viewBox="0 0 404 784">
                    <defs>
                        <pattern id="f210dbf6-a58d-4871-961e-36d5016a0f49" x="0" y="0" width="20" height="20"
                                 patternUnits="userSpaceOnUse">
                            <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor"/>
                        </pattern>
                    </defs>
                    <rect width="404" height="784" fill="url(#f210dbf6-a58d-4871-961e-36d5016a0f49)"/>
                </svg>
                <svg class="absolute left-full transform -translate-y-3/4 -translate-x-1/4 md:-translate-y-1/2 lg:-translate-x-1/2"
                     width="404" height="784" fill="none" viewBox="0 0 404 784">
                    <defs>
                        <pattern id="5d0dd344-b041-4d26-bec4-8d33ea57ec9b" x="0" y="0" width="20" height="20"
                                 patternUnits="userSpaceOnUse">
                            <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor"/>
                        </pattern>
                    </defs>
                    <rect width="404" height="784" fill="url(#5d0dd344-b041-4d26-bec4-8d33ea57ec9b)"/>
                </svg>
            </div>
        </div>

        <div class="relative pt-6 pb-16 sm:pb-24">
            <div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6">
                    <nav class="relative flex items-center justify-between sm:h-10 md:justify-center"
                         aria-label="Global">
                        <div class="flex items-center flex-1 md:absolute md:inset-y-0 md:left-0">
                            <div class="flex items-center justify-between w-full md:w-auto">
                                <a href="#">
                                    <span class="sr-only">Workflow</span>
                                    <img class="h-8 w-auto sm:h-10"
                                         src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="">
                                </a>
                                <div class="-mr-2 flex items-center md:hidden">
                                    <button type="button"
                                            class="bg-gray-50 rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                                            aria-expanded="false">
                                        <span class="sr-only">Open main menu</span>
                                        <!-- Heroicon name: outline/menu -->
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 6h16M4 12h16M4 18h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:flex md:space-x-10">
                            <a href="#" class="font-medium text-gray-500 hover:text-gray-900">Product</a>

                            <a href="#" class="font-medium text-gray-500 hover:text-gray-900">Features</a>

                            <a href="#" class="font-medium text-gray-500 hover:text-gray-900">Marketplace</a>

                            <a href="#" class="font-medium text-gray-500 hover:text-gray-900">Company</a>
                        </div>
                        <div class="hidden md:absolute md:flex md:items-center md:justify-end md:inset-y-0 md:right-0">
                            <span class="inline-flex rounded-md shadow">
                                <a href="#"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50">
                                    Log in
                                </a>
                            </span>
                        </div>
                    </nav>
                </div>

                <!--
                  Mobile menu, show/hide based on menu open state.

                  Entering: "duration-150 ease-out"
                    From: "opacity-0 scale-95"
                    To: "opacity-100 scale-100"
                  Leaving: "duration-100 ease-in"
                    From: "opacity-100 scale-100"
                    To: "opacity-0 scale-95"
                -->
                <div class="absolute top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden">
                    <div class="rounded-lg shadow-md bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
                        <div class="px-5 pt-4 flex items-center justify-between">
                            <div>
                                <img class="h-8 w-auto"
                                     src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="">
                            </div>
                            <div class="-mr-2">
                                <button type="button"
                                        class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                                    <span class="sr-only">Close menu</span>
                                    <!-- Heroicon name: outline/x -->
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="px-2 pt-2 pb-3">
                            <a href="#"
                               class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Product</a>

                            <a href="#"
                               class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Features</a>

                            <a href="#"
                               class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Marketplace</a>

                            <a href="#"
                               class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Company</a>
                        </div>
                        <a href="#"
                           class="block w-full px-5 py-3 text-center font-medium text-indigo-600 bg-gray-50 hover:bg-gray-100">
                            Log in
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-16 mx-auto max-w-7xl px-4 sm:mt-24">
                <div class="text-center">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Analyze <span class="text-indigo-600">qPCR Data</span> easier
                            than ever</span>
                    </h1>
                    <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                        Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo.
                        Elit sunt amet fugiat veniam occaecat fugiat aliqua.
                    </p>
                </div>
            </div>
            <div class="max-w-lg mx-auto mt-16">
                <div class="bg-white p-8 shadow-md rounded-lg">
                    <x-form action="http://example.com" class="space-y-6">
                        <x-secondary-button as="label">
                            <input type="file" class="hidden">
                            Choose .rdml File
                        </x-secondary-button>

                        <div class="grid md:grid-cols-3 md:items-center">
                            <div class="col-span-1">
                                <x-label for="target">Target</x-label>
                            </div>
                            <div class="col-span-2">
                                <x-input name="target"/>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-3 md:items-center">
                            <div class="col-span-1">
                                <x-label for="fluor">Fluor</x-label>
                            </div>
                            <div class="col-span-2">
                                <x-input name="fluor"/>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-3 md:items-center">
                            <div class="col-span-1">
                                <x-label for="pathogen">Pathogen</x-label>
                            </div>
                            <div class="col-span-2">
                                <x-input name="pathogen"/>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-3 md:items-center">
                            <div class="col-span-1">
                                <x-label for="quantify">Quantify</x-label>
                            </div>
                            <div class="col-span-2">
                                <label class="flex items-center">
                                    <x-checkbox name="quantify" value="1"/>
                                    <span class="ml-4">Yes</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-3 md:items-center">
                            <div class="col-span-1">
                                <x-label for="threshold">Threshold</x-label>
                            </div>
                            <div class="col-span-2">
                                <x-input name="threshold"/>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-3 md:items-center">
                            <div class="col-span-1">
                                <x-label for="cutoff">Cutoff</x-label>
                            </div>
                            <div class="col-span-2">
                                <x-input name="cutoff"/>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-3 md:items-center">
                            <div class="col-span-1">
                                <x-label for="cutoff_stddev">Cutoff Stddev</x-label>
                            </div>
                            <div class="col-span-2">
                                <x-input name="cutoff_stddev"/>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-3 md:items-center">
                            <div class="col-span-1">
                                <x-label for="slope">Slope</x-label>
                            </div>
                            <div class="col-span-2">
                                <x-input name="slope"/>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-3 md:items-center">
                            <div class="col-span-1">
                                <x-label for="intercept">Intercept</x-label>
                            </div>
                            <div class="col-span-2">
                                <x-input name="intercept"/>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-3 md:items-center">
                            <div class="col-span-1">
                                <x-label for="repetitions">Required repetitions</x-label>
                            </div>
                            <div class="col-span-2">
                                <x-input name="repetitions" type="number" min="1" value="1"/>
                            </div>
                        </div>

                        <x-primary-button type="submit">
                            Analyze
                        </x-primary-button>
                    </x-form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
