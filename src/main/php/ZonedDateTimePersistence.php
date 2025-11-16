<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

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
        return $zonedDateTime !== null ? JavaSe8\Time::toLocalDateTime(
            JavaSe8\Time::zonedDateTime($zonedDateTime)->setTimezone(new DateTimeZone('UTC')),
        ) : null;
    }

    /**
     * @note use named arguments for arguments after `$_`, {@see https://www.php.net/manual/en/functions.arguments.php#functions.named-arguments}
     *
     * @param ($format is null ? DateTimeInterface|null : string|null) $utcDateTime
     * @param null $_ named arguments separator
     * @param ($format is null ? DateTimeInterface|null : string|null) $localDateTime
     *
     * @return ($utcDateTime is null ? null : ZonedDateTime)
     *
     * @throws Exception\ZonedDateTimePersistenceCouldNotComputeZonedDateTime
     */
    public static function computeZonedDateTime(
        DateTimeInterface|string|null $utcDateTime,
        $_ = null,
        DateTimeInterface|string|null $localDateTime = null,
        string|null $format = null,
    ): DateTimeImmutable|null {
        if ($format === null) {
            $utcDateTime = JavaSe8\Time::localDateTime($utcDateTime);
            $localDateTime = JavaSe8\Time::localDateTime($localDateTime);
        } else {
            try {
                $utcDateTime = $utcDateTime !== null ? DateTimeUtils::parseAsLocalDateTime($utcDateTime, $format) : null;
                $localDateTime = $localDateTime !== null ? DateTimeUtils::parseAsLocalDateTime($localDateTime, $format) : null;
            } catch (Exception\DateTimeUtilsCouldNotParseAsLocalDateTime $cause) {
                throw new Exception\ZonedDateTimePersistenceCouldNotComputeZonedDateTime($cause);
            }
        }
        return match (true) {
            $localDateTime !== null => self::computeZonedDateTimeFromUtcDateTimeAndLocalDateTime($utcDateTime, $localDateTime),
            default => self::computeZonedDateTimeFromUtcDateTime($utcDateTime),
        };
    }

    /**
     * @param LocalDateTime|null $utcDateTime
     *
     * @return ZonedDateTime|null
     */
    private static function computeZonedDateTimeFromUtcDateTime(
        DateTimeInterface|null $utcDateTime,
    ): DateTimeImmutable|null {
        return $utcDateTime !== null ? DateTimeUtils::asUtcInstantAtOffset($utcDateTime, 0)
            ->setTimezone(new DateTimeZone(date_default_timezone_get())) : null;
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
}
