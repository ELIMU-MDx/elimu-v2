<?php

use Domain\Rdml\Services\RdmlFileReader;
use Illuminate\Http\File;
use JetBrains\PhpStorm\Pure;
use Tests\UnitTestCase;
use ZipArchive;

uses(UnitTestCase::class);

it('converts an rdml file to xml', function () {
    $file = new File($this->resourcePath('example.rdml'));

    $this->assertXmlStringEqualsXmlString(
        file_get_contents($this->resourcePath('example.xml')),
        getReader()->read($file)
    );
});

// Helpers
function getReader#[Pure]
    private function getReader(): RdmlFileReader
    {
        return new RdmlFileReader(new ZipArchive());
    }
