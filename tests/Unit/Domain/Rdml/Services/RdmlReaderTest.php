<?php

namespace Tests\Unit\Domain\Rdml\Services;

use Domain\Rdml\Services\RdmlFileReader;
use Illuminate\Http\File;
use JetBrains\PhpStorm\Pure;
use Tests\UnitTestCase;
use ZipArchive;

final class RdmlReaderTest extends UnitTestCase
{
    /** @test */
    public function itConvertsAnRdmlFileToXml(): void
    {
        $file = new File($this->resourcePath('example.rdml'));

        $this->assertXmlStringEqualsXmlString(
            file_get_contents($this->resourcePath('example.xml')),
            $this->getReader()->read($file)
        );
    }

    #[Pure]
    private function getReader(): RdmlFileReader
    {
        return new RdmlFileReader(new ZipArchive());
    }
}
