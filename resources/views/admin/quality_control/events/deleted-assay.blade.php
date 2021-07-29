<p class="text-sm font-medium text-indigo-600 truncate">
    {{ucfirst($log->event)}} {{class_basename($log->subject_type)}} {{$log->changes->get('old')['name']}}
</p>
