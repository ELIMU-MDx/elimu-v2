<?php

declare(strict_types=1);

namespace Domain\Rdml\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self SAMPLE()
 * @method static self POSTIVE_CONTROL()
 * @method static self NEGATIVE_CONTROL()
 * @method static self NTC_CONTROL()
 */
final class MeasurementType extends Enum
{
    public static function byString(string $type): MeasurementType
    {
        return match ($type) {
            'pos' => self::POSTIVE_CONTROL(),
            'ntc' => self::NTC_CONTROL(),
            'neg' => self::NEGATIVE_CONTROL(),
            default => self::SAMPLE()
        };
    }

    protected static function labels()
    {
        return [
            'SAMPLE' => 'sample',
            'POSTIVE_CONTROL' => 'positive control',
            'NEGATIVE_CONTROL' => 'negative control',
            'NTC_CONTROL' => 'ntc control',
        ];
    }

}
