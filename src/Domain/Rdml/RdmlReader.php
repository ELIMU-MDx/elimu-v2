<?php

declare(strict_types=1);

namespace Domain\Rdml;

use Illuminate\Http\File;
use InvalidArgumentException;
use ZipArchive;

final class RdmlReader
{
    private array $errorCodes = [
        ZipArchive::ER_EXISTS => 'The archive exists already',
        ZipArchive::ER_INCONS => 'The archive is inkonsistent',
        ZipArchive::ER_INVAL => 'Invalid argument',
        ZipArchive::ER_MEMORY => 'Malloc-Error',
        ZipArchive::ER_NOENT => 'No such file',
        ZipArchive::ER_NOZIP => 'No archive',
        ZipArchive::ER_OPEN => 'File could not be opened',
        ZipArchive::ER_READ => 'File could not be read',
        ZipArchive::ER_SEEK => 'Pointer error',
    ];

    public function __construct(private ZipArchive $zipArchive)
    {
    }

    public function read(File $file): string
    {
        $result = $this->zipArchive->open($file->getRealPath(), ZipArchive::RDONLY);

        if($result !== true) {
            throw new InvalidArgumentException("Archive could not be opened '{$this->errorCodes[$result]}'");
        }

        if ($this->zipArchive->numFiles > 1) {
            $this->zipArchive->close();
            throw new InvalidArgumentException("An rdml file can only contain 1 file. {$archive->numFiles} found");
        }

        $xmlContent = $this->zipArchive->getFromIndex(0);
        $this->zipArchive->close();

        return $xmlContent;
    }
}
