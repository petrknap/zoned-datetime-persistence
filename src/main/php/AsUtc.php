<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

/**
 * Eloquent factory
 */
final class AsUtc
{
    private function __construct()
    {
    }

    /**
     * @see AsUtcDateTime
     *
     * @return string cast
     */
    public static function dateTime(
        string|null $dateTimeFormat = null,
    ): string {
        return AsUtcDateTime::class . ':' . $dateTimeFormat;
    }

    /**
     * @note you can cast used attributes {@see AsPrivate}
     *
     * @see UtcWithLocal
     */
    public static function withLocal(
        string $utcDateTimeAttributeName,
        string $localDateTimeAttributeName,
        string $dateTimeFormat,
    ): Attribute {
        return Attribute::make(
            get: static fn (mixed $_, array $attributes): Carbon|null => self::toNullableCarbon(
                UtcWithLocal::fromFormattedValues(
                    utcDateTime: $attributes[$utcDateTimeAttributeName] ?? null,
                    localDateTime: $attributes[$localDateTimeAttributeName] ?? null,
                    dateTimeFormat: $dateTimeFormat,
                )?->toZonedDateTime(),
            ),
            set: static function (DateTimeInterface|null $value) use ($utcDateTimeAttributeName, $localDateTimeAttributeName, $dateTimeFormat): array {
                $utcWithLocal = $value !== null ? new UtcWithLocal($value) : null;
                return [
                    $utcDateTimeAttributeName => $utcWithLocal?->getUtcDateTime(format: $dateTimeFormat),
                    $localDateTimeAttributeName => $utcWithLocal?->getLocalDateTime(format: $dateTimeFormat),
                ];
            },
        );
    }

    /**
     * @note you can cast used attribute {@see AsPrivate}
     *
     * @see UtcWithSystemTimezone
     */
    public static function withSystemTimezone(
        string $utcDateTimeAttributeName,
        string $dateTimeFormat,
    ): Attribute {
        return Attribute::make(
            get: static fn (mixed $_, array $attributes): Carbon|null => self::toNullableCarbon(
                UtcWithSystemTimezone::fromFormattedValue(
                    utcDateTime: $attributes[$utcDateTimeAttributeName] ?? null,
                    dateTimeFormat: $dateTimeFormat,
                )?->toZonedDateTime(),
            ),
            set: static function (DateTimeInterface|null $value) use ($utcDateTimeAttributeName, $dateTimeFormat): array {
                $utcWithSystemTimezone = $value !== null ? new UtcWithSystemTimezone($value) : null;
                return [
                    $utcDateTimeAttributeName => $utcWithSystemTimezone?->getUtcDateTime(format: $dateTimeFormat),
                ];
            },
        );
    }

    /**
     * @note you can cast used attributes {@see AsPrivate}
     *
     * @see UtcWithTimezone
     */
    public static function withTimezone(
        string $utcDateTimeAttributeName,
        string $dateTimeFormat,
        string $timezoneAttributeName,
    ): Attribute {
        return Attribute::make(
            get: static fn (mixed $_, array $attributes): Carbon|null => self::toNullableCarbon(
                UtcWithTimezone::fromFormattedValues(
                    utcDateTime: $attributes[$utcDateTimeAttributeName] ?? null,
                    dateTimeFormat: $dateTimeFormat,
                    timezone: $attributes[$timezoneAttributeName] ?? null,
                )?->toZonedDateTime(),
            ),
            set: static function (DateTimeInterface|null $value) use ($utcDateTimeAttributeName, $dateTimeFormat, $timezoneAttributeName): array {
                $utcWithTimezone = $value !== null ? new UtcWithTimezone($value) : null;
                return [
                    $utcDateTimeAttributeName => $utcWithTimezone?->getUtcDateTime(format: $dateTimeFormat),
                    $timezoneAttributeName => $utcWithTimezone?->getTimezone(formatted: true),
                ];
            },
        );
    }

    private static function toNullableCarbon(DateTimeInterface|null $dateTime): Carbon|null
    {
        /** @var Carbon|null */
        return $dateTime !== null ? Carbon::createFromInterface($dateTime) : null;
    }
}
