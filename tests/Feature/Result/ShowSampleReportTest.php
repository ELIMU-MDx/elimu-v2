<?php

use Database\Factories\SampleFactory;

it('shows a pdf report for a sample', function () {
    $sample = SampleFactory::new()
        ->withAllData()
        ->create();
})->skip('Test not implemented');
