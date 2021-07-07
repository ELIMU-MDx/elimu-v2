<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class DataCollectionSoftware extends DataTransferObject implements Arrayable
{
    public string $name;

    public string $version;
}
