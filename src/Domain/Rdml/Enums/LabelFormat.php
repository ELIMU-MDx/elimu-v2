<?php

declare(strict_types=1);

namespace Domain\Rdml\Enums;

use BadMethodCallException;
use Spatie\Enum\Laravel\Enum;

enum LabelFormat : string
{
    case NUMBERS = 'NUMBERS';
    case ABC = 'ABC';
    case A1A1 = 'A1a1';
}
