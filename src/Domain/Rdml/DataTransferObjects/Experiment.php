<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Domain\Rdml\Collections\RunCollection;
use Spatie\DataTransferObject\DataTransferObject;

final class Experiment extends DataTransferObject
{
    public string $id;

    public ?string $description;

    public RunCollection $runs;
}
