<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

/**
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 */
final class ZonedDateTimePersistence
{
    private function __construct()
    {
    }

    /**
     * @return ($zonedDateTime is null ? null : LocalDateTime)
     */
    public static function computeUtcDateTime(DateTimeInterface|null $zonedDateTime): DateTimeImmutable|null
    {
        return $zonedDateTime !== null
            ? JavaSe8\Time::toLocalDateTime(JavaSe8\Time::zonedDateTime($zonedDateTime)->setTimezone(new DateTimeZone('UTC')))
            : null;
    }

    /**
     * @note use named arguments for arguments after `$_`, {@see https://www.php.net/manual/en/functions.arguments.php#functions.named-arguments}
     *
     * @param null $_ named arguments separator
     *
     * @return ($utcDateTime is null ? null : ZonedDateTime)
     */
    public static function computeZonedDateTime(
        DateTimeInterface|null $utcDateTime,
        $_ = null,
        DateTimeInterface|null $localDateTime = null,
        DateTimeZone|null $timezone = null,
    ): DateTimeImmutable|null {
        $utcDateTime = JavaSe8\Time::localDateTime($utcDateTime);
        $localDateTime = JavaSe8\Time::localDateTime($localDateTime);

        return match (true) {
            $localDateTime !== null => self::computeZonedDateTimeFromUtcDateTimeAndLocalDateTime($utcDateTime, $localDateTime),
            $timezone !== null => self::computeZonedDateTimeFromUtcDateTimeAndTimezone($utcDateTime, $timezone),
            $utcDateTime === null => null,
            default => throw new InvalidArgumentException('Too few arguments'),
        };
    }

    /**
     * @param LocalDateTime|null $utcDateTime
     * @param LocalDateTime|null $localDateTime
     *
     * @return ZonedDateTime|null
     */
    private static function computeZonedDateTimeFromUtcDateTimeAndLocalDateTime(
        DateTimeImmutable|null $utcDateTime,
        DateTimeImmutable|null $localDateTime,
    ): DateTimeImmutable|null {
        return $utcDateTime !== null && $localDateTime !== null ? DateTimeUtils::asUtcInstantAtOffset(
            $utcDateTime,
            DateTimeUtils::secondsBetween($utcDateTime, $localDateTime),
        ) : null;
    }

    /**
     * @param LocalDateTime|null $utcDateTime
     *
     * @return ZonedDateTime|null
     */
    private static function computeZonedDateTimeFromUtcDateTimeAndTimezone(
        DateTimeImmutable|null $utcDateTime,
        DateTimeZone|null $timezone = null,
    ): DateTimeImmutable|null {
        return $utcDateTime !== null && $timezone !== null ? DateTimeUtils::asUtcInstantAtOffset(
            $utcDateTime,
            0,
        )->setTimezone($timezone) : null;
    }
}
