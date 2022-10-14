<?php

use Domain\Rdml\Services\RdmlFileReader;
use Illuminate\Http\File;
use function PHPUnit\Framework\assertXmlStringEqualsXmlString;

it('converts an rdml file to xml', function () {
    $file = new File(resourcePath('example.rdml'));

    assertXmlStringEqualsXmlString(
        file_get_contents(resourcePath('example.xml')),
        getReader()->read($file)
    );
});
function getReader(): RdmlFileReader
{
    return new RdmlFileReader(new ZipArchive());
}
