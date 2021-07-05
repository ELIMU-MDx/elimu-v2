<?php

declare(strict_types=1);

namespace Domain\Rdml;

use Alchemy\Zippy\Zippy;
use Illuminate\Http\File;
use ZipArchive;

final class RdmlReader
{
    public function __construct(private Zippy $zippy)
    {
    }

    public function read(File $file): string
    {
        $this->zippy->open($file->getRealPath(), 'zip');
        $this->zipArchive->open($file->getRealPath(), R);
    }
}
