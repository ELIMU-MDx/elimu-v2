<?php

declare(strict_types=1);

namespace Domain\Study\Enums;

use Spatie\Enum\Laravel\Enum;

enum InvitationStatus : string
{
    case PENDING = 'PENDING';
    case ACCEPTED = 'ACCEPTED';
    case REJECTED = 'REJECTED';
}
