<?php

declare(strict_types=1);

use function app;
use function collect;
use Domain\Experiment\DataTransferObjects\ResultCalculationParameter;
use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\Enums\QualitativeResult;
use Domain\Results\Services\MeasurementEvaluator;


it('evaluates to positive', function () {
    $evaluator = app(MeasurementEvaluator::class);
    $measurements = measurements([['cq' => 2], ['cq' => 3]]);

    $results = $evaluator->results($measurements, collect([
        new ResultCalculationParameter([
            'target' => 'ab',
            'cutoff' => 10,
        ]),
    ]));

    $this->assertCount(1, $results);
    $this->assertEquals(2.5, $results->first()->averageCQ->rounded());
    $this->assertEquals(QualitativeResult::POSITIVE(), $results->first()->qualification);
});

it('evaluates to negative', function (float $cutoff, array $cqs, ?float $averageCq) {
    $evaluator = app(MeasurementEvaluator::class);
    $measurements = measurements($cqs);

    $results = $evaluator->results($measurements, collect([
        new ResultCalculationParameter([
            'target' => 'ab',
            'cutoff' => $cutoff,
        ]),
    ]));

    $this->assertCount(1, $results);
    $this->assertEquals($averageCq, $results->first()->averageCQ->rounded());
    $this->assertEquals(QualitativeResult::NEGATIVE(), $results->first()->qualification);
})->with('negativeDataSet');

// Datasets
dataset('negativeDataSet', [
    'exceeds cutoff' => [
        12.0,
        [['cq' => 10], ['cq' => 15]],
        12.5,
    ],
    'is null' => [
        12.0,
        [['cq' => null], ['cq' => null]],
        null,
    ],
    'is less than zero' => [
        12.0,
        [['cq' => -2], ['cq' => -1]],
        -1.5,
    ],
]);

// Helpers
function measurements(array $parameters): MeasurementCollection
{
    $measurements = [];
    foreach ($parameters as $parameter) {
        $measurements[] = new Measurement(array_merge([
            'sample' => 'xy',
            'target' => 'ab',
            'position' => 'x',
            'excluded' => false,
            'type' => MeasurementType::SAMPLE(),
            'amplificationDataPoints' => collect(),
        ], $parameter));
    }

    return MeasurementCollection::make($measurements);
}
