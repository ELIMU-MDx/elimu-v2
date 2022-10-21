<?php

use Database\Factories\SampleFactory;

it('shows a pdf report for a sample', function () {
    SampleFactory::new()
        ->withAllData()
        ->create();
});
