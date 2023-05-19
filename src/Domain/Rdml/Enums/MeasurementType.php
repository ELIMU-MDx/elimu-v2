<?php

declare(strict_types=1);

namespace Domain\Rdml\Enums;

enum MeasurementType: string
{
    case SAMPLE = 'SAMPLE';
    case POSTIVE_CONTROL = 'POSITIVE_CONTROL';
    case NEGATIVE_CONTROL = 'NEGATIVE_CONTROL';
    case NTC_CONTROL = 'NTC_CONTROL';
    case STANDARD = 'STANDARD';

    public static function byString(string $type): MeasurementType
    {
        return match ($type) {
            'pos' => self::POSTIVE_CONTROL,
            'ntc' => self::NTC_CONTROL,
            'neg' => self::NEGATIVE_CONTROL,
            'std' => self::STANDARD,
            default => self::SAMPLE,
        };
    }

    public static function toString(MeasurementType $type): string
    {
        return match ($type) {
            self::POSTIVE_CONTROL => 'positive control',
            self::NTC_CONTROL => 'ntc control',
            self::NEGATIVE_CONTROL => 'negative control',
            self::STANDARD => 'standard',
            default => 'sample',
        };
    }

    /**
     * @return array<int, MeasurementType>
     */
    public static function controls(): array
    {
        return [
            self::POSTIVE_CONTROL,
            self::NTC_CONTROL,
            self::NEGATIVE_CONTROL,
        ];
    }

    public static function isControl(MeasurementType $type): bool
    {
        return in_array($type, static::controls(), true);
    }
}
