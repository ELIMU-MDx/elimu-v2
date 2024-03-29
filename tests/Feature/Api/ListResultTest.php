<?php

declare(strict_types=1);

use Database\Factories\AssayFactory;
use Database\Factories\MeasurementFactory;
use Database\Factories\ResultFactory;
use Database\Factories\UserFactory;

it('list results', function () {
    $assay = AssayFactory::new()->create();
    $result = ResultFactory::new(['assay_id' => $assay->id])
        ->withMeasurement(MeasurementFactory::new()->included()->sample())
        ->create();

    $response = $this->signIn(UserFactory::new()->withStudy($assay->study)->create())
        ->getJson(route('api.results.index', $assay));

    $response->assertOk();
    $response->assertJsonCount(1);
    $this->assertEquals([
        [
            'sample' => $result->sample->identifier,
            'replicas_'.strtolower($result->target) => 1,
            'replicas_'.strtolower($result->target).'_total' => 1,
            'mean_cq_'.strtolower($result->target) => $result->cq !== null ? round($result->cq, 12) : null,
            'qualitative_result_'.strtolower($result->target) => $result->qualification->value,
            'quantitative_result_'.strtolower($result->target) => $result->quantification,
            'standard_deviation_cq_'.strtolower($result->target) => $result->standard_deviation,
        ],
    ], $response->json());
});

it('lists empty results for assays that do not belong to the user', function () {
    $assay = AssayFactory::new()->create();
    $result = ResultFactory::new(['assay_id' => $assay->id])->withMeasurement()->create();

    $this->signIn(UserFactory::new()->withStudy()->create())
        ->getJson(route('api.results.index', $assay))
        ->assertOk()
        ->assertJson([]);
});

it('lists only samples', function () {
    $assay = AssayFactory::new()->create();
    $result = ResultFactory::new(['assay_id' => $assay->id])
        ->withMeasurement(MeasurementFactory::new()->ntc())
        ->create();

    $this->signIn(UserFactory::new()->withStudy($assay->study)->create())
        ->getJson(route('api.results.index', $assay))
        ->assertOk()
        ->assertExactJson([])
        ->assertJsonCount(0);
});
