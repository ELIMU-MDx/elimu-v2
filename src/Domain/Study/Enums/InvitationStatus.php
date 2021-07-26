<?php

declare(strict_types=1);

namespace Domain\Study\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self PENDING()
 * @method static self ACCEPTED()
 * @method static self REJECTED()
 */
final class InvitationStatus extends Enum
{
}
