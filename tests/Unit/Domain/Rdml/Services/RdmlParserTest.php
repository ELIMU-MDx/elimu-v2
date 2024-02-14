<?php

declare(strict_types=1);

use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\DataTransferObjects\Rdml;
use Domain\Rdml\RdmlParser;

use function Spatie\Snapshots\assertMatchesJsonSnapshot;

it('converts a xml to dto', function () {
    $xml = file_get_contents(resourcePath('example.xml'));

    $parser = new RdmlParser();

    $rdml = $parser->extract($xml);

    expect($rdml)->toBeInstanceOf(Rdml::class);
});

it('converts an xml', function () {
    $xml = file_get_contents(resourcePath('example.xml'));

    $parser = new RdmlParser();

    $rdml = $parser->extract($xml);

    $this->assertMatchesJsonSnapshot(json_encode($rdml->toArray(), JSON_THROW_ON_ERROR));
});

it('converts an xml with standards', function () {
    $xml = file_get_contents(resourcePath('example-with-std.xml'));

    $parser = new RdmlParser();

    $rdml = $parser->extract($xml);

    $this->assertNotEmpty($rdml->measurements->standards());

    assertMatchesJsonSnapshot(json_encode($rdml->toArray(), JSON_THROW_ON_ERROR));
});

it('ignores duplicated measurements', function () {
    $xml = file_get_contents(resourcePath('example.xml'));

    $parser = new RdmlParser();

    $rdml = $parser->extract($xml);

    $uniqueMeasurements = $rdml->measurements->sortBy('sample')
        ->unique(fn (Measurement $measurement) => $measurement->target.$measurement->sample.$measurement->position.$measurement->cq);

    $this->assertEquals($rdml->measurements->count(), $uniqueMeasurements->count());
});

it('has data points for measurements without cq values', function () {
    $xml = file_get_contents(resourcePath('example.xml'));

    $parser = new RdmlParser();

    $rdml = $parser->extract($xml);

    $measurement = $rdml->measurements->first(fn (Measurement $measurement) => $measurement->cq !== null);

    $this->assertNotEmpty($measurement->amplificationDataPoints);
});
