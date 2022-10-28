<x-pdf-layout>
    <div class="max-w-screen-md min-h-screen mx-auto p-10 flex flex-col">
        <header class="border-b-2 border-gray-900 pb-2">
            <p class="font-bold">{{$report->study}}</p>
        </header>
        <div class="py-10 flex-grow flex flex-col">
            <h1 class="text-2xl text-center">Laboratory Result Form for {{$report->assayName}} RT-qPCR</h1>

            <p class="text-xl mt-6">Result for sample ID: <strong>{{$report->sampleId}}</strong></p>

            <div class="mt-6">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-200">
                    <tr>
                        <th scope="col"
                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                            Quality control
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                            Result
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-gray-50">
                    @foreach($report->controlTargets as $target)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                Internal Control ({{$target->name}})
                            </td>

                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">
                                <span
                                    class="font-bold">
                                    {{$target->qualification}}
                                </span>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="space-y-4 mt-12">
                <h2 class="font-bold">Analysis result</h2>

                <div>
                    <div class="mt-4">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-200">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    {{$report->assayName}} analyte
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Cq value
                                </th>
                                @if($report->hasQuantification)
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        <p>Quantification</p>
                                    </th>
                                @endif
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Result
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-gray-50">
                            @foreach($report->targets as $target)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{$target->name}}</td>

                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{new \Support\ValueObjects\RoundedNumber($target->cq)}}</td>
                                    @if($report->hasQuantification)
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{new \Support\ValueObjects\RoundedNumber($target->quantification)}}</td>
                                    @endif

                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">@if($target->qualification === \Domain\Results\Enums\QualitativeResult::POSITIVE())
                                            <span
                                                class="font-bold">
                                                Positive
                                            </span>
                                        @else
                                            <span
                                                class="font-bold">
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

            <img src="{{$report->qrCode}}" alt="QR Code" class="w-64 block mx-auto"/>
            <div class="grid grid-cols-2 grid-rows-2 gap-6 mt-auto pt-4">
                <p class="row-start-1">Report released</p>

                <p class="row-start-2">{{date('d.m.Y')}}</p>

                <p class="row-start-1">Signature</p>
                <div class="mt-6 h-px bg-gray-900 w-full row-start-2"></div>
            </div>
        </div>

        <footer class="flex border-t-2 border-gray-900 mt-8 justify-between pt-2">
            <p class="font-bold">ELIMU-MDx</p>
        </footer>
    </div>
</x-pdf-layout>
