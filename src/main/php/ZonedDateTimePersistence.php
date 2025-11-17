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
     * @param ($format is null ? DateTimeInterface|null : string|null) $utcDateTime
     * @param ($format is null ? DateTimeInterface|null : string|null) $localDateTime
     *
     * @return ($utcDateTime is null ? null : ZonedDateTime)
     *
     * @throws Exception\ZonedDateTimePersistenceCouldNotComputeZonedDateTime
     */
    public static function computeZonedDateTime(
        DateTimeInterface|string|null $utcDateTime,
        DateTimeInterface|string|null $localDateTime,
        string|null $format = null,
    ): DateTimeImmutable|null {
        if ($utcDateTime === null || $localDateTime === null) {
            return null;
        }
        if ($format === null) {
            $utcDateTime = JavaSe8\Time::localDateTime($utcDateTime);
            $localDateTime = JavaSe8\Time::localDateTime($localDateTime);
        } else {
            try {
                $utcDateTime = DateTimeUtils::parseAsLocalDateTime($utcDateTime, $format);
                $localDateTime = DateTimeUtils::parseAsLocalDateTime($localDateTime, $format);
            } catch (Exception\DateTimeUtilsCouldNotParseAsLocalDateTime $cause) {
                throw new Exception\ZonedDateTimePersistenceCouldNotComputeZonedDateTime($cause);
            }
        }
        return DateTimeUtils::asUtcInstantAtOffset(
            $utcDateTime,
            DateTimeUtils::secondsBetween($utcDateTime, $localDateTime),
        );
    }
}
