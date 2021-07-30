<p class="text-sm font-medium text-indigo-600 truncate">
    {{$log->getExtraProperty('attributes.excluded') ? 'Excluded' : 'Included'}} Measurement
</p>
@include('admin.quality_control.events.attributes', ['data' =>
[
    'CQ Value' => $log->getExtraProperty('attributes.cq'),
    'Target' => $log->getExtraProperty('attributes.target'),
    'Sample' => $log->getExtraProperty('attributes')['sample.identifier'],
    'Experiment' => $log->getExtraProperty('attributes')['experiment.name'],
]
])

