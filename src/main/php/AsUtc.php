<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

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
}
