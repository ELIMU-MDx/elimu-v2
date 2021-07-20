<?php

declare(strict_types=1);

namespace Domain\Rdml;

use Domain\Rdml\DataTransferObjects\Rdml;
use Symfony\Component\HttpFoundation\File\File;

final class RdmlReader
{
    public function __construct(
        private RdmlFileReader $fileReader,
        private RdmlParser $rdmlParser,
    ) {
    }

    public function read(File $file): Rdml
    {
        return $this->rdmlParser->extract($this->fileReader->read($file));
    }
}
