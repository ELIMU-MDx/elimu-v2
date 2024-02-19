<?php

use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\Enums\MeasurementType;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class, LazilyRefreshDatabase::class)
    ->beforeEach(function () {
        $this->withoutVite();
    })
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

function measurements(array $parameters): MeasurementCollection
{
    $measurements = [];
    foreach ($parameters as $parameter) {
        $measurements[] = new Measurement(...array_merge([
            'sample' => 'xy',
            'target' => 'ab',
            'position' => 'x',
            'excluded' => false,
            'type' => MeasurementType::SAMPLE,
            'amplificationDataPoints' => collect(),
        ], $parameter));
    }

    return MeasurementCollection::make($measurements);
}
function resourcePath(string $path): string
{
    return __DIR__.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.ltrim($path, '\/\\');
}
