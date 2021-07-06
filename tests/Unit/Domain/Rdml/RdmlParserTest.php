<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Rdml;

use Domain\Rdml\DataTransferObjects\Rdml;
use Domain\Rdml\RdmlParser;
use Tests\UnitTestCase;

final class RdmlParserTest extends UnitTestCase
{
    /** @test */
    public function itConvertsAXmlToDto(): void
    {
        $xml = file_get_contents($this->resourcePath('example.xml'));

        $parser = new RdmlParser();

        $rdml = $parser->extract($xml);

        $this->assertInstanceOf(Rdml::class, $rdml);
    }
}
