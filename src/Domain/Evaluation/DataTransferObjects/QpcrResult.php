<?php

declare(strict_types=1);

namespace Domain\Evaluation\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class QpcrResult extends DataTransferObject implements Arrayable
{
    public string $sampleId;

    /** @var \Domain\Evaluation\DataTransferObjects\TargetResult[] */
    public array $targets;
}
