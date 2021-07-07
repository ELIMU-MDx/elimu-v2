<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class Step extends DataTransferObject implements Arrayable
{
    public int $number;

    public ?string $description;

    public Temperature $temperature;
}
