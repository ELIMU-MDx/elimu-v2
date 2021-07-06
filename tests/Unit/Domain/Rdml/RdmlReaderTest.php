<?php

namespace Tests\Unit\Domain\Rdml;

use Domain\Rdml\RdmlReader;
use Illuminate\Http\File;
use JetBrains\PhpStorm\Pure;
use Tests\UnitTestCase;
use ZipArchive;

class RdmlReaderTest extends UnitTestCase
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
    private function getReader(): RdmlReader
    {
        return new RdmlReader(new ZipArchive());
    }
}
