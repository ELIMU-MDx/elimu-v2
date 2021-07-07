<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Domain\Rdml\Collections\RunCollection;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class Experiment extends DataTransferObject implements Arrayable
{
    public string $id;

    public ?string $description;

    public RunCollection $runs;
}
