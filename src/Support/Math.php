<?php

declare(strict_types=1);

namespace Support;

use Domain\Results\Enums\QualitativeResult;

final class Math
{
    public static function qualifyCq(?float $cq, float $cutoff): QualitativeResult
    {
        return $cq !== null && $cq > 0 && $cq <= $cutoff
            ? QualitativeResult::POSITIVE
            : QualitativeResult::NEGATIVE;
    }
}
