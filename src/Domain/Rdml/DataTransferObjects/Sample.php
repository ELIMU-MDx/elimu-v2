<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class Sample extends DataTransferObject implements Arrayable
{
    public string $id;

    public string $type;

    public ?string $description;
}
