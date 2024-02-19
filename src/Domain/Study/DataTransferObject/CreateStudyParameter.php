<?php

declare(strict_types=1);

namespace Domain\Study\DataTransferObject;

use Support\Data;

final class CreateStudyParameter extends Data
{
    public function __construct(
        public string $identifier,
        public string $name,
    ) {

    }
}
