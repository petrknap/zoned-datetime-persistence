<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use PetrKnap\Eloquent\Casts\AsPrivate;

/**
 * Eloquent factory
 */
abstract class AsUtc
{
    /**
     * @see AsUtcDateTime
     *
     * @return string cast
     */
    public static function dateTime(
        string|null $dateTimeFormat = null,
        bool|null $readonly = null,
    ): string {
        return AsUtcDateTime::class . ':' . $dateTimeFormat . ',' . $readonly;
    }

    /**
     * @note you can cast used attributes as readonly {@see AsUtc::dateTime()} or {@see AsPrivate}
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
     * @note you can cast used attribute as readonly {@see AsUtc::dateTime()} or {@see AsPrivate}
     * @note the set method supports string $value as it serves as an alternative to {@see AsUtcDateTime}
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
            set: static function (DateTimeInterface|string|null $value) use ($utcDateTimeAttributeName, $dateTimeFormat): array {
                return [
                    $utcDateTimeAttributeName => $value instanceof DateTimeInterface
                        ? (new UtcWithSystemTimezone($value))->getUtcDateTime(format: $dateTimeFormat)
                        : $value,
                ];
            },
        );
    }

    /**
     * @note you can cast used attributes as readonly {@see AsUtc::dateTime()} or {@see AsPrivate}
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

    /**
     * @note the set method supports string $value as it serves as an alternative to {@see AsUtcDateTime}
     *
     * @see UtcWithTimezone
     */
    public static function withUtc(
        string $utcDateTimeAttributeName,
        string $dateTimeFormat,
    ): Attribute {
        return Attribute::make(
            get: static fn (mixed $_, array $attributes): Carbon|null => self::toNullableCarbon(
                UtcWithTimezone::fromFormattedValues(
                    utcDateTime: $attributes[$utcDateTimeAttributeName] ?? null,
                    dateTimeFormat: $dateTimeFormat,
                    timezone: 'UTC',
                )?->toZonedDateTime(),
            ),
            set: static function (DateTimeInterface|string|null $value) use ($utcDateTimeAttributeName, $dateTimeFormat): array {
                return [
                    $utcDateTimeAttributeName => $value instanceof DateTimeInterface
                        ? (new UtcWithTimezone($value))->getUtcDateTime(format: $dateTimeFormat)
                        : $value,
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
