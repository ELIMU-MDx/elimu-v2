<p class="text-sm font-medium text-indigo-600 truncate">
    {{ucfirst($log->event)}} {{class_basename($log->subject_type)}} {{$log->changes->get('attributes')['name']}}
</p>
@include('admin.quality_control.events.attributes', ['data' =>collect($log->changes->get('attributes', []))->except('name')])
