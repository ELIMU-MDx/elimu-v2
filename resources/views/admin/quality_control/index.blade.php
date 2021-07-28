<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Audit log</h1>
        </div>
    </x-slot>

    <div class="py-12">
        @if($logs->isEmpty())
            <div class="text-left">
                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No log entries yet</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Start doing some activities to fill the audit log
                </p>
            </div>
        @else

            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($logs as $log)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex justify-between items-stretch space-x-4">
                                    <div class="flex flex-col items-start justify-between">
                                        @includeFirst(['admin.quality_control.events.'. $log->event . '-' .strtolower(class_basename($log->subject_type)), 'admin.quality_control.events.'. $log->event, 'admin.quality_control.events.default'])
                                    </div>

                                    <div class="flex flex-col items-end justify-between flex-shrink-0">
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100">
                                            {{$log->causer->name}}
                                        </p>
                                        <div class="mt-2 text-sm text-gray-500">
                                            <p>
                                                <time datetime="{{$log->created_at->format('Y-m-d')}}">{{ $log->created_at->format('F j, Y') }}</time>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if ($logs->hasPages())
                <div class="mt-3">
                    {{$logs->links()}}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
