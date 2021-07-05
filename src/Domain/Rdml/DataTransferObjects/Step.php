<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class Step extends DataTransferObject
{
    public int $number;

    public ?string $description;

    public Temperature $temperature;
}
