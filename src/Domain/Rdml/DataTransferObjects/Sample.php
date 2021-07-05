<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class Sample extends DataTransferObject
{
    public string $id;

    public string $type;

    public ?string $description;
}
