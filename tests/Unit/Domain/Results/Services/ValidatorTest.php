<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Results\Services;

use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\DataTransferObjects\Result;
use Domain\Results\DataTransferObjects\ResultValidationParameter;
use Domain\Results\Enums\QualitativeResult;
use Domain\Results\ResultValidationErrors\ControlValidationError;
use Domain\Results\ResultValidationErrors\DivergingMeasurementsError;
use Domain\Results\ResultValidationErrors\NotEnoughRepetitionsError;
use Domain\Results\ResultValidationErrors\StandardDeviationExceedsCutoffError;
use Domain\Results\Services\ResultValidator;
use PHPUnit\Framework\TestCase;
use Support\RoundedNumber;

use function app;
use function collect;

final class ValidatorTest extends TestCase
{
    /**
     * @test
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function itIsAValidResult(): void
    {
        $validator = app(ResultValidator::class);
        $result = $this->result(['cq' => [12, 12]]);
        $parameter = new ResultValidationParameter([
            'requiredRepetitions' => 2,
            'standardDeviationCutoff' => 1,
            'cutoff' => 10,
        ]);

        $errors = $validator->validate($result, $parameter);

        $this->assertEmpty($errors, 'Found errors '.$errors->join(', '));
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    private function result(array $parameters): Result
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
            'measurements' => $this->measurements(
                $cqs->map(function ($cq) {
                    return ['cq' => $cq];
                })->toArray(),
            ),
            'type' => MeasurementType::SAMPLE(),
        ], $parameters));
    }

    private function measurements(array $parameters): MeasurementCollection
    {
        $measurements = [];
        foreach ($parameters as $parameter) {
            $measurements[] = new Measurement(array_merge([
                'sample' => 'xy',
                'target' => 'ab',
                'position' => 'x',
                'excluded' => false,
                'type' => MeasurementType::SAMPLE(),
            ], $parameter));
        }

        return MeasurementCollection::make($measurements);
    }

    /**
     * @test
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function itHasATooHighStandardDeviation(): void
    {
        $validator = app(ResultValidator::class);
        $result = $this->result(['cq' => [5, 10]]);
        $parameter = new ResultValidationParameter([
            'requiredRepetitions' => 2,
            'standardDeviationCutoff' => 1,
            'cutoff' => 50,
        ]);

        $errors = $validator->validate($result, $parameter);

        $this->assertCount(1, $errors);
        $this->assertContains(StandardDeviationExceedsCutoffError::IDENTIFIER, $errors);
    }

    /**
     * @test
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function itHasDivergingResults(): void
    {
        $validator = app(ResultValidator::class);
        $result = $this->result(['cq' => [null, 10]]);
        $parameter = new ResultValidationParameter([
            'requiredRepetitions' => 2,
            'standardDeviationCutoff' => 10,
            'cutoff' => 50,
        ]);

        $errors = $validator->validate($result, $parameter);

        $this->assertCount(1, $errors);
        $this->assertContains(DivergingMeasurementsError::IDENTIFIER, $errors);
    }

    /**
     * @test
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function itHasNotEnoughRepetitions(): void
    {
        $validator = app(ResultValidator::class);
        $result = $this->result(['cq' => [10]]);
        $parameter = new ResultValidationParameter([
            'requiredRepetitions' => 2,
            'standardDeviationCutoff' => 10,
            'cutoff' => 50,
        ]);

        $errors = $validator->validate($result, $parameter);

        $this->assertCount(1, $errors);
        $this->assertContains(NotEnoughRepetitionsError::IDENTIFIER, $errors, 'Found errors: '.$errors->join(', '));
    }

    /**
     * @test
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @dataProvider controlsDataSet
     */
    public function itValidatesControls(
        string|float $validationOption,
        ?float $cq,
        MeasurementType $type,
        bool $resultsInError = false
    ): void {
        $validator = app(ResultValidator::class);
        $result = $this->result(['cq' => [$cq], 'type' => $type]);
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
            $this->assertCount(1, $errors);
            $this->assertContains(ControlValidationError::IDENTIFIER, $errors);
        } else {
            $this->assertEmpty($errors);
        }
    }

    public function controlsDataSet(): array
    {
        return [
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
        ];
    }
}
