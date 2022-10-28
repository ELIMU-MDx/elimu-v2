<?php

use Database\Factories\SampleFactory;
use Illuminate\Routing\Middleware\ValidateSignature;

it('shows a pdf report for a sample', function () {
    $sample = SampleFactory::new()
        ->withAllData()
        ->create();
})->skip('Test not implemented');
