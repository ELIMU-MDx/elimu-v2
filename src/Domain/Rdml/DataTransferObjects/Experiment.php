<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class Experiment extends DataTransferObject
{
    public string $id;

    public ?string $description;

    public array $runs;
}
