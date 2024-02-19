<?php

use App\Livewire\ListResults;
use Database\Factories\AssayFactory;
use Database\Factories\MeasurementFactory;
use Database\Factories\ResultFactory;
use Database\Factories\StudyFactory;
use Database\Factories\UserFactory;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

it('lists samples', function () {
    $study = StudyFactory::new()->create();
    $assay = AssayFactory::new()->recycle($study)->hasParameters()->create();
    ResultFactory::new(['assay_id' => $assay->id])
        ->recycle($study)
        ->recycle($assay)
        ->forParameter($assay->parameters[0])
        ->withMeasurement(MeasurementFactory::new()->sample())
        ->create();

    $this->signIn(UserFactory::new()->withStudy($study)->create());
    /** @var LengthAwarePaginator $samples */
    $samples = Livewire::test(ListResults::class)
        ->get('samples');

    $this->assertCount(1, $samples->items());
});
it('does not list non samples', function () {
    $study = StudyFactory::new()->create();
    $assay = AssayFactory::new()->recycle($study)->create();
    $result = ResultFactory::new(['assay_id' => $assay->id])
        ->recycle($study)
        ->withMeasurement(MeasurementFactory::new()->standard())
        ->create();

    $this->signIn(UserFactory::new()->withStudy($study)->create());
    /** @var LengthAwarePaginator $samples */
    $samples = Livewire::test(ListResults::class)
        ->get('samples');

    $this->assertCount(0, $samples->items());
});
