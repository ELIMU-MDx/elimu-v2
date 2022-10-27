<x-pdf-layout>
    <div class="max-w-screen-md min-h-screen mx-auto p-10 flex flex-col">
        <header class="border-b-2 border-gray-900 pb-2">
            <img alt="Organisation logo"
                 src="https://www.swisstph.ch/typo3temp/assets/_processed_/e/8/csm_Logo_SwissTPH_print_e17aa7820d.png"
                 class="max-w-[200px]"/>
        </header>
        <div class="py-10 flex-grow flex flex-col">
            <h1 class="text-2xl text-center">Laboratory Result Form for {{$assay->name}} RT-qPCR</h1>

            <p class="text-xl mt-6">Result for sample ID: <strong>{{$sample->identifier}}</strong></p>

            <div class="space-y-4 mt-12">
                <h2 class="font-bold">Analysis result</h2>

                    <div>
                        <div class="mt-4">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-200">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                        {{$assay->name}} analyte
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        CV value
                                    </th>
                                    @if($sample->results->first(fn($result) => $result->quantification))
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            <p>Quantification</p>
                                            <p>eggs / g stool)</p>
                                        </th>
                                    @endif
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Result
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-gray-50">
                                @foreach($sample->results->where('assay_id', $assay->id) as $result)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{$result->target}}</td>

                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$result->cq}}</td>
                                        @if($sample->results->first(fn($result) => $result->quantification))
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$result->quantification}}</td>
                                        @endif

                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">@if($result->qualification === 'POSITIVE')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Positive
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Negative
                                                </span>
                                            @endif</td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>

            <p class="mt-12">
                Sample tested [Positive/Negative] for {{$assay->name}} by RT-qPCR
            </p>

            <div class="grid grid-cols-2 gap-6 mt-auto">
                <div>
                    <p>Date</p>
                    <div class="mt-6 h-px bg-gray-900 w-full"></div>
                </div>

                <div>
                    <p>Signature</p>
                    <div class="mt-6 h-px bg-gray-900 w-full"></div>
                </div>
            </div>
        </div>

        <footer class="flex border-t-2 border-gray-900 mt-8 justify-between">
            <p class="font-bold">Elimu - MDx</p>
            <p class="page-number"></p>
            <p>Version {{date('d.m.Y')}}</p>
        </footer>
    </div>
</x-pdf-layout>
