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
        $this->filters['position'] = $this->sample->measurements->pluck('position')->unique()->values()->toArray();
        $this->filters['target'] = $this->sample->measurements->pluck('target')->unique()->values()->toArray();
        $this->filters['experiment'] = $this->sample->measurements->pluck('experiment.name')->unique()->values()->toArray();
    }

    public function dehydrateFilters($value)
    {
        ray($value);
    }

    private function getFilteredMeasurements()
    {
        ray($this->filters);
        $factory = app(MeasurementFilterFactory::class);
        $filters = collect($this->filters)
            ->map(fn($arguments, $filterName) => $factory->get($filterName, [$arguments]));

        return $this->sample
            ->measurements
            ->reject(fn(Measurement $measurement) => $filters
                    ->first(fn(MeasurementFilter $filter) => !$filter->matches($measurement)) !== null
            );
    }

    public function getSeriesProperty(): array
    {
        return $this->getFilteredMeasurements()
            ->map(fn(Measurement $measurement) => [
                'name' => "$measurement->position $measurement->target",
                'color' => $this->graphColor($measurement->position, $measurement->target),
                'data' => $measurement
                    ->dataPoints
                    ->map(fn(DataPoint $dataPoint) => $dataPoint->fluor)
                    ->toArray(),
            ])
            ->sortBy('name')
            ->values()
            ->toArray();
    }

    private function graphColor(string $position, string $target): string
    {
        $colors = [
            // lime
            0 => ['#a3e635', '#65a30d', '#365314'],
            // blue
            1 => ['#60a5fa', '#2563eb', '#1e3a8a'],
            // red
            2 => ['#fb7185', '#e11d48', '#881337'],
            // purple
            3 => ['#c084fc', '#9333ea', '#581c87'],
        ];

        $category = $colors[$this->sample->measurements->pluck('position')->unique()->sort()->values()->search($position) % 4];
        return $category[$this->sample->measurements->pluck('target')->sort()->unique()->values()->search($target) % 3];
    }

    public function render()
    {
        $options = [
            'colors' => ['#F44336', '#F44336', '#F44336', '#FF00FF', '#FF00FF', '#FF00FF'],
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
