<?php

declare(strict_types=1);

namespace Domain\Study\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class CreateStudyParameter extends DataTransferObject
{
    public string $identifier;

    public string $name;
}
