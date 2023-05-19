<?php

declare(strict_types=1);

namespace Domain\Results\Enums;

enum QualitativeResult: string
{
    case POSITIVE = 'POSITIVE';
    case NEGATIVE = 'NEGATIVE';
}
