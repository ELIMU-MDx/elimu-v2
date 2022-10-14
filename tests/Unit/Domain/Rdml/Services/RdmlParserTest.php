<?php

declare(strict_types=1);

use Domain\Rdml\DataTransferObjects\Rdml;
use Domain\Rdml\Services\RdmlParser;
use Spatie\Snapshots\MatchesSnapshots;
use Tests\UnitTestCase;

uses(UnitTestCase::class);
uses(MatchesSnapshots::class);

it('converts a xml to dto', function () {
    $xml = file_get_contents($this->resourcePath('example.xml'));

    $parser = new RdmlParser();

    $rdml = $parser->extract($xml);

    $this->assertInstanceOf(Rdml::class, $rdml);
});

it('converts an xml', function () {
    $xml = file_get_contents($this->resourcePath('example.xml'));

    $parser = new RdmlParser();

    $rdml = $parser->extract($xml);

    $this->assertMatchesJsonSnapshot(json_encode($rdml->toArray(), JSON_THROW_ON_ERROR));
});

it('converts an xml with standards', function () {
    $xml = file_get_contents($this->resourcePath('example-with-std.xml'));

    $parser = new RdmlParser();

    $rdml = $parser->extract($xml);

    $this->assertNotEmpty($rdml->measurements->standards());

    $this->assertMatchesJsonSnapshot(json_encode($rdml->toArray(), JSON_THROW_ON_ERROR));
});
