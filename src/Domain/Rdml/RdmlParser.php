<?php

declare(strict_types=1);

namespace Domain\Rdml;

use Domain\Rdml\DataTransferObjects\Rdml;
use Illuminate\Http\File;

final class RdmlParser
{
    public function extract(File $file): Rdml
    {
        // extract xml from zip
        // parse xml to rdml dto

    }

    private function get(File $file): string
    {
    }
}
