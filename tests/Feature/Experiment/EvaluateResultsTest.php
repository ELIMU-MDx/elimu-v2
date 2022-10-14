<?php

declare(strict_types=1);

use Database\Factories\AssayFactory;
use Database\Factories\AssayParameterFactory;
use Database\Factories\ExperimentFactory;
use Database\Factories\MeasurementFactory;
use Database\Factories\SampleFactory;
use Domain\Experiment\Actions\RecalculateResultsAction;
use Domain\Results\Enums\QualitativeResult;
use Domain\Results\Models\Result;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


/**
 *
 * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
 */
it('evaluates results', function () {
    $measurements = MeasurementFactory::new(['target' => 'foo', 'excluded' => false])
        ->for(SampleFactory::new())
        ->for(ExperimentFactory::new()
            ->for(AssayFactory::new()->hasParameters(
                AssayParameterFactory::new([
                    'target' => 'foo',
                    'required_repetitions' => 4,
                    'cutoff' => 10,
                    'standard_deviation_cutoff' => 20,
                    'slope' => -0.02,
                    'intercept' => 1,
                ])
            ))
        )
        ->count(2)
        ->state(new Sequence(
            ['cq' => 2],
            ['cq' => 4]
        ))
        ->create();

    app(RecalculateResultsAction::class)->execute($measurements);

    $this->assertDatabaseHas('results', [
        'sample_id' => $measurements[0]->sample_id,
        'target' => 'foo',
        'cq' => 3,
        'quantification' => 8.71,
        'qualification' => QualitativeResult::POSITIVE(),
        'standard_deviation' => 0.71,
    ]);
    $this->assertDatabaseCount('results', 1);
    $this->assertDatabaseHas('result_errors', [
        'error' => 'Only 2 repetitions instead of 4',
    ]);

    $this->assertDatabaseHas('measurements', [
        'id' => $measurements->first()->id,
        'result_id' => Result::latest()->first()->id,
    ]);
});
