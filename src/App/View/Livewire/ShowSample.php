<?php

namespace App\View\Livewire;

use App\Admin\Experiments\Filters\MeasurementFilter;
use App\Admin\Experiments\Filters\MeasurementFilterFactory;
use Domain\Experiment\Models\DataPoint;
use Domain\Experiment\Models\Measurement;
use Domain\Experiment\Models\Sample;
use Livewire\Component;

class ShowSample extends Component
{
    public Sample $sample;

    public array $filters = [
        'excluded' => false,
        'experiment' => [],
        'position' => [],
        'target' => [],
    ];

    public function mount(Sample $sample)
    {
        $this->sample = $sample;

        $this->sample->load(['measurements.dataPoints', 'measurements.experiment']);
    }

    private function getFilteredMeasurements()
    {
        $factory = app(MeasurementFilterFactory::class);
        $filters = collect($this->filters)
            ->map(fn ($arguments, $filterName) => $factory->get($filterName, [$arguments]));

        return $this->sample
            ->measurements
            ->reject(fn (Measurement $measurement) => $filters
                    ->first(fn (MeasurementFilter $filter) => ! $filter->matches($measurement)) !== null
            );
    }

    public function getSeriesProperty(): array
    {
        return $this->getFilteredMeasurements()
            ->map(fn (Measurement $measurement) => [
                'name' => "$measurement->position $measurement->target",
                'data' => $measurement
                    ->dataPoints
                    ->map(fn (DataPoint $dataPoint) => $dataPoint->fluor)
                    ->toArray(),
            ])
            ->sortBy('name')
            ->values()
            ->toArray();
    }

    public function render()
    {
        $options = [
            'chart' => [
                'type' => 'line',
                'zoom' => [
                    'enabled' => false,
                ],
                'animations' => [
                    'speed' => 800,
                    'animateGradually' => [
                        'enabled' => false,
                    ],
                ],
            ],
            'series' => $this->getSeriesProperty(),
            'stroke' => [
                'curve' => 'straight',
            ],
            'yaxis' => [
                'title' => [
                    'text' => 'fluor',
                    'style' => [
                        'cssClass' => 'font-sans text-sm font-bold text-lg',
                    ],
                ],
            ],
            'xaxis' => [
                'title' => [
                    'text' => 'cycle',
                    'style' => [
                        'cssClass' => 'font-sans text-sm font-bold text-lg',
                    ],
                ],
            ],
            'grid' => [
                'row' => [
                    'colors' => ['#f3f3f3', 'transparent'],
                    'opacity' => 0.5,
                ],
            ],
        ];

        $this->emit('updated', ['series' => $this->getSeriesProperty()]);

        return view('livewire.show-sample', ['options' => $options]);
    }
}
