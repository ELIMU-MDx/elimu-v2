<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class ReactionData extends DataTransferObject
{
    public bool $excluded = false;

    public Target $target;

    public ?float $cq;

    /** @var \Domain\Rdml\DataTransferObjects\AmplificationDataPoint[] */
    public array $amplificationDataPoints;

    /** @var \Domain\Rdml\DataTransferObjects\MeltingCurveDataPoint[] */
    public array $meltingDataPoints;
}
