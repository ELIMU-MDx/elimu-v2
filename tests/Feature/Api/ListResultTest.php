<?php

declare(strict_types=1);

use Database\Factories\AssayFactory;
use Database\Factories\ResultFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


it('list results', function () {
    $assay = AssayFactory::new()->create();
    $result = ResultFactory::new(['assay_id' => $assay->id])->withMeasurement()->create();

    $this->signIn(UserFactory::new()->withStudy($assay->study)->create())
        ->getJson(route('api.results.index', $assay))
        ->assertOk()
        ->assertJson([
            [
                'sample' => $result->sample->identifier,
                'replicas_'.strtolower($result->target) => 1,
                'mean_cq_'.strtolower($result->target) => $result->cq,
                'qualitative_result_'.strtolower($result->target) => $result->qualification->label,
                'quantitative_result_'.strtolower($result->target) => $result->quantification,
                'standard_deviation_cq_'.strtolower($result->target) => $result->standard_deviation,
            ],
        ])
        ->assertJsonCount(1);
});

it('lists empty results for assays that do not belong to the user', function () {
    $assay = AssayFactory::new()->create();
    $result = ResultFactory::new(['assay_id' => $assay->id])->withMeasurement()->create();

    $this->signIn(UserFactory::new()->withStudy()->create())
        ->getJson(route('api.results.index', $assay))
        ->assertOk()
        ->assertJson([]);
});
