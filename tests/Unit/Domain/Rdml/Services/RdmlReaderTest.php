<?php

use Domain\Rdml\Services\RdmlFileReader;
use Illuminate\Http\File;
use Tests\UnitTestCase;

uses(UnitTestCase::class);

it('converts an rdml file to xml', function () {
    $file = new File($this->resourcePath('example.rdml'));

    $this->assertXmlStringEqualsXmlString(
        file_get_contents($this->resourcePath('example.xml')),
        getReader()->read($file)
    );
});
function getReader(): RdmlFileReader
{
    return new RdmlFileReader(new ZipArchive());
}
