<?php

declare(strict_types=1);

namespace Domain\Results\Enums;

use Spatie\Enum\Laravel\Enum;

enum QualitativeResult : string
{
    case POSITIVE = 'POSITIVE';
    case NEGATIVE = 'NEGATIVE';
}
