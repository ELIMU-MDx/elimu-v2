<p class="text-sm font-medium text-indigo-600 truncate">
    {{ucfirst($log->event)}} {{class_basename($log->subject_type)}}
</p>
@include('admin.quality_control.events.attributes', ['data' => $log->changes->get('attributes', [])])
