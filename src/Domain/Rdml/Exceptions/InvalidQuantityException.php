<?php

namespace Domain\Rdml\Exceptions;

use Domain\Rdml\DataTransferObjects\Measurement;
use Exception;

final class InvalidQuantityException extends Exception
{
    public static function forStandard(Measurement $measurement): never
    {
        throw new static("Invalid quantity '{$measurement->quantity}' for standard '{$measurement->sample}'");
    }
}
