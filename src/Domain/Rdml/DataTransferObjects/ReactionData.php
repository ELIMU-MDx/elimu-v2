<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Domain\Rdml\Collections\AmplificationDataCollection;
use Domain\Rdml\Collections\MeltingCurveDataCollection;
use Spatie\DataTransferObject\DataTransferObject;

final class ReactionData extends DataTransferObject
{
    public bool $excluded = false;

    public Target $target;

    public ?float $cq;

    public AmplificationDataCollection $amplificationDataPoints;

    public MeltingCurveDataCollection $meltingDataPoints;
}
