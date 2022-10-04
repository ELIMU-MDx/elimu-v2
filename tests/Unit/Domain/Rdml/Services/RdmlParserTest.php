<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Rdml\Services;

use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\DataTransferObjects\Rdml;
use Domain\Rdml\Services\RdmlParser;
use Spatie\Snapshots\MatchesSnapshots;
use Tests\UnitTestCase;

final class RdmlParserTest extends UnitTestCase
{
    use MatchesSnapshots;

    /** @test */
    public function itConvertsAXmlToDto(): void
    {
        $xml = file_get_contents($this->resourcePath('example.xml'));

        $parser = new RdmlParser();

        $rdml = $parser->extract($xml);

        $this->assertInstanceOf(Rdml::class, $rdml);
    }

    /** @test */
    public function itConvertsAnXml(): void
    {
        $xml = file_get_contents($this->resourcePath('example.xml'));

        $parser = new RdmlParser();

        $rdml = $parser->extract($xml);

        $this->assertMatchesJsonSnapshot(json_encode($rdml->toArray(), JSON_THROW_ON_ERROR));
    }

    /** @test */
    public function itConvertsAnXmlWithStandards(): void
    {
        $xml = file_get_contents($this->resourcePath('example-with-std.xml'));

        $parser = new RdmlParser();

        $rdml = $parser->extract($xml);

        $this->assertNotEmpty($rdml->measurements->standards());

        $this->assertMatchesJsonSnapshot(json_encode($rdml->toArray(), JSON_THROW_ON_ERROR));
    }
}
