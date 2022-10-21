<?php

declare(strict_types=1);

namespace Domain\Results\DataTransferObjects;

use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Results\Enums\QualitativeResult;
use Spatie\DataTransferObject\DataTransferObject;
use Support\ValueObjects\RoundedNumber;

final class Result extends DataTransferObject
{
    public string | int $sample;

    public string $target;

    public RoundedNumber $averageCQ;

    public int $repetitions;

    public QualitativeResult $qualification;

    public ?RoundedNumber $quantification = null;

    public MeasurementCollection $measurements;

    public MeasurementType $type;
}
