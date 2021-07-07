<?php

declare(strict_types=1);

namespace Domain\Evaluation\DataTransferObjects;

use Domain\Evaluation\Collections\TargetDataCollection;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class SampleData extends DataTransferObject implements Arrayable
{
    public string $id;

    public TargetDataCollection $targets;
}
