<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class DataCollectionSoftware extends DataTransferObject
{
    public string $name;

    public string $version;
}
