<?php

declare(strict_types=1);

namespace Domain\Rdml\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self SAMPLE()
 * @method static self POSTIVE_CONTROL()
 * @method static self NEGATIVE_CONTROL()
 * @method static self NTC_CONTROL()
 * @method static self STANDARD()
 */
final class MeasurementType extends Enum
{
    public static function byString(string $type): MeasurementType
    {
        return match ($type) {
            'pos' => self::POSTIVE_CONTROL(),
            'ntc' => self::NTC_CONTROL(),
            'neg' => self::NEGATIVE_CONTROL(),
            'std' => self::STANDARD(),
            default => self::SAMPLE()
        };
    }

    public static function controls(): array
    {
        return [
            self::POSTIVE_CONTROL(),
            self::NTC_CONTROL(),
            self::NEGATIVE_CONTROL(),
        ];
    }

    public static function isControl(MeasurementType $type): bool
    {
        return in_array($type, static::controls(), true);
    }

    protected static function labels()
    {
        return [
            'SAMPLE' => 'sample',
            'POSTIVE_CONTROL' => 'positive control',
            'NEGATIVE_CONTROL' => 'negative control',
            'NTC_CONTROL' => 'ntc control',
            'STANDARD' => 'standard',
        ];
    }
}
