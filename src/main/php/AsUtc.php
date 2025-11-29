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
     * @note you can cast raw attributes {@see AsPrivate}
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
                    $attributes[$utcDateTimeAttributeName] ?? null,
                    $attributes[$localDateTimeAttributeName] ?? null,
                    $dateTimeFormat,
                )?->toZonedDateTime(),
            ),
            set: static function (DateTimeInterface|null $value) use ($utcDateTimeAttributeName, $localDateTimeAttributeName, $dateTimeFormat): array {
                $utcWithLocal = $value !== null ? new UtcWithLocal($value) : null;
                return [
                    $utcDateTimeAttributeName => $utcWithLocal?->getUtcDateTime($dateTimeFormat),
                    $localDateTimeAttributeName => $utcWithLocal?->getLocalDateTime($dateTimeFormat),
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
