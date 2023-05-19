<?php

declare(strict_types=1);

use Domain\Experiment\DataTransferObjects\ResultCalculationParameter;
use Domain\Results\Enums\QualitativeResult;
use Domain\Results\MeasurementEvaluator;

it('evaluates to positive', function () {
    $evaluator = app(MeasurementEvaluator::class);
    $measurements = measurements([['cq' => 2], ['cq' => 3]]);

    $results = $evaluator->results($measurements, collect([
        new ResultCalculationParameter([
            'target' => 'ab',
            'cutoff' => 10,
        ]),
    ]));

    expect($results)->toHaveCount(1);
    expect($results->first()->averageCQ->rounded())->toEqual(2.5);
    expect($results->first()->qualification)->toEqual(QualitativeResult::POSITIVE());
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

    expect($results)->toHaveCount(1);
    expect($results->first()->averageCQ->rounded())->toEqual($averageCq);
    expect($results->first()->qualification)->toEqual(QualitativeResult::NEGATIVE());
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
