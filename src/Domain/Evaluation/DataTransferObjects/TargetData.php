<?php

declare(strict_types=1);

namespace Domain\Evaluation\DataTransferObjects;

use Domain\Evaluation\Collections\DataPointCollection;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class TargetData extends DataTransferObject implements Arrayable
{
    public string $id;

    public DataPointCollection $dataPoints;

    /** @var string[] */
    public array $errors = [];
}
