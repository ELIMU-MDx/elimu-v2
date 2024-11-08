<?php

declare(strict_types=1);
use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Domain\Results\Enums\QualitativeResult;
use Domain\Results\ResultValidationErrors\DivergingMeasurementsError;
use Domain\Results\ResultValidationErrors\NotEnoughRepetitionsError;
use Domain\Results\ResultValidationErrors\StandardDeviationExceedsCutoffError;
use Domain\Results\ResultValidator;
use Support\ValueObjects\RoundedNumber;

it('is a valid result', function () {
    $validator = app(ResultValidator::class);
    $result = result(['cq' => [12, 12]]);
    $parameter = new ResultValidationParameter(
        requiredRepetitions: 2,
        cutoff: 10,
        standardDeviationCutoff: 1,
    );

    $errors = $validator->validate($result, $parameter);

    $this->assertEmpty($errors, 'Found errors '.$errors->join(', '));
});

it('has a too high standard deviation', function () {
    $validator = app(ResultValidator::class);
    $result = result(['cq' => [5, 10]]);
    $parameter = new ResultValidationParameter(
        requiredRepetitions: 2,
        cutoff: 50,
        standardDeviationCutoff: 1,
    );

    $errors = $validator->validate($result, $parameter);

    expect($errors)->toHaveCount(1);
    expect($errors)->toContain(StandardDeviationExceedsCutoffError::IDENTIFIER);
});

it('has diverging results', function () {
    $validator = app(ResultValidator::class);
    $result = result(['cq' => [null, 10]]);
    $parameter = new ResultValidationParameter(
        requiredRepetitions: 2,
        cutoff: 50,
        standardDeviationCutoff: 10,
    );

    $errors = $validator->validate($result, $parameter);

    expect($errors)->toHaveCount(1);
    expect($errors)->toContain(DivergingMeasurementsError::IDENTIFIER);
});

it('has not enough repetitions', function () {
    $validator = app(ResultValidator::class);
    $result = result(['cq' => [10]]);
    $parameter = new ResultValidationParameter(
        requiredRepetitions: 2,
        cutoff: 50,
        standardDeviationCutoff: 10,
    );

    $errors = $validator->validate($result, $parameter);

    expect($errors)->toHaveCount(1);
    $this->assertContains(NotEnoughRepetitionsError::IDENTIFIER, $errors, 'Found errors: '.$errors->join(', '));
});

// Datasets
dataset('controlsDataSet', [
    'positive control with null validation and error' => [
        'null',
        12,
        MeasurementType::POSTIVE_CONTROL,
        true,
    ],
    'positive control with cutoff validation and error' => [
        'cutoff',
        51,
        MeasurementType::POSTIVE_CONTROL,
        true,
    ],
    'positive control with custom parameter validation and error' => [
        11,
        12,
        MeasurementType::POSTIVE_CONTROL,
        true,
    ],
    'negative control with null validation and error' => [
        'null',
        12,
        MeasurementType::NEGATIVE_CONTROL,
        true,
    ],
    'negative control with cutoff validation and error' => [
        'cutoff',
        51,
        MeasurementType::NEGATIVE_CONTROL,
        true,
    ],
    'negative control with custom parameter validation and error' => [
        11,
        12,
        MeasurementType::NEGATIVE_CONTROL,
        true,
    ],
    'ntc control with null validation and error' => [
        'null',
        12,
        MeasurementType::NTC_CONTROL,
        true,
    ],
    'ntc control with cutoff validation and error' => [
        'cutoff',
        51,
        MeasurementType::NTC_CONTROL,
        true,
    ],
    'ntc control with custom parameter validation and error' => [
        11,
        12,
        MeasurementType::NTC_CONTROL,
        true,
    ],
    'positive control with null validation' => [
        'null',
        null,
        MeasurementType::POSTIVE_CONTROL,
    ],
    'positive control with cutoff validation' => [
        'cutoff',
        50,
        MeasurementType::POSTIVE_CONTROL,
    ],
    'positive control with custom parameter validation' => [
        12,
        12,
        MeasurementType::POSTIVE_CONTROL,
    ],
    'negative control with null validation' => [
        'null',
        null,
        MeasurementType::NEGATIVE_CONTROL,
    ],
    'negative control with cutoff validation' => [
        'cutoff',
        50,
        MeasurementType::NEGATIVE_CONTROL,
    ],
    'negative control with custom parameter validation' => [
        12,
        12,
        MeasurementType::NEGATIVE_CONTROL,
    ],
    'ntc control with null validation' => [
        'null',
        null,
        MeasurementType::NTC_CONTROL,
    ],
    'ntc control with cutoff validation' => [
        'cutoff',
        50,
        MeasurementType::NTC_CONTROL,
    ],
    'ntc control with custom parameter validation' => [
        12,
        12,
        MeasurementType::NTC_CONTROL,
    ],
]);

// Helpers
function result(array $parameters): Result
{
    $cqs = collect($parameters['cq'] ?? [12, 12]);
    unset($parameters['cq']);

    return new Result(...array_merge([
        'sample' => 'xy',
        'target' => 'ab',
        'averageCQ' => new RoundedNumber($cqs->avg()),
        'repetitions' => $cqs->count(),
        'qualification' => QualitativeResult::POSITIVE,
        'quantification' => null,
        'measurements' => test()->measurements(
            $cqs->map(fn ($cq) => ['cq' => $cq])->toArray(),
        ),
        'type' => MeasurementType::SAMPLE,
    ], $parameters));
}
