<?php

declare(strict_types=1);

use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Domain\Results\Enums\QualitativeResult;
use Domain\Results\ResultValidationErrors\ControlValidationError;
use Domain\Results\ResultValidationErrors\DivergingMeasurementsError;
use Domain\Results\ResultValidationErrors\NotEnoughRepetitionsError;
use Domain\Results\ResultValidationErrors\StandardDeviationExceedsCutoffError;
use Domain\Results\Services\ResultValidator;
use Support\RoundedNumber;

/**
 * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
 */
it('is a valid result', function () {
    $validator = app(ResultValidator::class);
    $result = result(['cq' => [12, 12]]);
    $parameter = new ResultValidationParameter([
        'requiredRepetitions' => 2,
        'standardDeviationCutoff' => 1,
        'cutoff' => 10,
    ]);

    $errors = $validator->validate($result, $parameter);

    $this->assertEmpty($errors, 'Found errors '.$errors->join(', '));
});

/**
 * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
 */
it('has a too high standard deviation', function () {
    $validator = app(ResultValidator::class);
    $result = result(['cq' => [5, 10]]);
    $parameter = new ResultValidationParameter([
        'requiredRepetitions' => 2,
        'standardDeviationCutoff' => 1,
        'cutoff' => 50,
    ]);

    $errors = $validator->validate($result, $parameter);

    expect($errors)->toHaveCount(1);
    expect($errors)->toContain(StandardDeviationExceedsCutoffError::IDENTIFIER);
});

/**
 * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
 */
it('has diverging results', function () {
    $validator = app(ResultValidator::class);
    $result = result(['cq' => [null, 10]]);
    $parameter = new ResultValidationParameter([
        'requiredRepetitions' => 2,
        'standardDeviationCutoff' => 10,
        'cutoff' => 50,
    ]);

    $errors = $validator->validate($result, $parameter);

    expect($errors)->toHaveCount(1);
    expect($errors)->toContain(DivergingMeasurementsError::IDENTIFIER);
});

/**
 * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
 */
it('has not enough repetitions', function () {
    $validator = app(ResultValidator::class);
    $result = result(['cq' => [10]]);
    $parameter = new ResultValidationParameter([
        'requiredRepetitions' => 2,
        'standardDeviationCutoff' => 10,
        'cutoff' => 50,
    ]);

    $errors = $validator->validate($result, $parameter);

    expect($errors)->toHaveCount(1);
    $this->assertContains(NotEnoughRepetitionsError::IDENTIFIER, $errors, 'Found errors: '.$errors->join(', '));
});

/**
 * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
 */
it('validates controls', function (
    string|float $validationOption,
    ?float $cq,
    MeasurementType $type,
    bool $resultsInError = false
) {
    $validator = app(ResultValidator::class);
    $result = result(['cq' => [$cq], 'type' => $type]);
    $parameter = new ResultValidationParameter([
        'requiredRepetitions' => 2,
        'standardDeviationCutoff' => 10,
        'cutoff' => 50,
        'positiveControl' => $validationOption,
        'negativeControl' => $validationOption,
        'ntcControl' => $validationOption,
    ]);

    $errors = $validator->validate($result, $parameter);

    if ($resultsInError) {
        expect($errors)->toHaveCount(1);
        expect($errors)->toContain(ControlValidationError::IDENTIFIER);
    } else {
        expect($errors)->toBeEmpty();
    }
})->with('controlsDataSet');

// Datasets
dataset('controlsDataSet', [
    'positive control with null validation and error' => [
        'null',
        12,
        MeasurementType::POSTIVE_CONTROL(),
        true,
    ],
    'positive control with cutoff validation and error' => [
        'cutoff',
        51,
        MeasurementType::POSTIVE_CONTROL(),
        true,
    ],
    'positive control with custom parameter validation and error' => [
        11,
        12,
        MeasurementType::POSTIVE_CONTROL(),
        true,
    ],
    'negative control with null validation and error' => [
        'null',
        12,
        MeasurementType::NEGATIVE_CONTROL(),
        true,
    ],
    'negative control with cutoff validation and error' => [
        'cutoff',
        51,
        MeasurementType::NEGATIVE_CONTROL(),
        true,
    ],
    'negative control with custom parameter validation and error' => [
        11,
        12,
        MeasurementType::NEGATIVE_CONTROL(),
        true,
    ],
    'ntc control with null validation and error' => [
        'null',
        12,
        MeasurementType::NTC_CONTROL(),
        true,
    ],
    'ntc control with cutoff validation and error' => [
        'cutoff',
        51,
        MeasurementType::NTC_CONTROL(),
        true,
    ],
    'ntc control with custom parameter validation and error' => [
        11,
        12,
        MeasurementType::NTC_CONTROL(),
        true,
    ],
    'positive control with null validation' => [
        'null',
        null,
        MeasurementType::POSTIVE_CONTROL(),
    ],
    'positive control with cutoff validation' => [
        'cutoff',
        50,
        MeasurementType::POSTIVE_CONTROL(),
    ],
    'positive control with custom parameter validation' => [
        12,
        12,
        MeasurementType::POSTIVE_CONTROL(),
    ],
    'negative control with null validation' => [
        'null',
        null,
        MeasurementType::NEGATIVE_CONTROL(),
    ],
    'negative control with cutoff validation' => [
        'cutoff',
        50,
        MeasurementType::NEGATIVE_CONTROL(),
    ],
    'negative control with custom parameter validation' => [
        12,
        12,
        MeasurementType::NEGATIVE_CONTROL(),
    ],
    'ntc control with null validation' => [
        'null',
        null,
        MeasurementType::NTC_CONTROL(),
    ],
    'ntc control with cutoff validation' => [
        'cutoff',
        50,
        MeasurementType::NTC_CONTROL(),
    ],
    'ntc control with custom parameter validation' => [
        12,
        12,
        MeasurementType::NTC_CONTROL(),
    ],
]);

// Helpers
/**
 * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
 */
function result(array $parameters): Result
{
    $cqs = collect($parameters['cq'] ?? [12, 12]);
    unset($parameters['cq']);

    return new Result(array_merge([
        'sample' => 'xy',
        'target' => 'ab',
        'averageCQ' => new RoundedNumber($cqs->avg()),
        'repetitions' => $cqs->count(),
        'qualification' => QualitativeResult::POSITIVE(),
        'quantification' => null,
        'measurements' => test()->measurements(
            $cqs->map(function ($cq) {
                return ['cq' => $cq];
            })->toArray(),
        ),
        'type' => MeasurementType::SAMPLE(),
    ], $parameters));
}
