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
     * @return LocalDateTime
     */
    public static function computeUtcDateTime(DateTimeInterface $zonedDateTime): DateTimeImmutable
    {
        return JavaSe8\Time::toLocalDateTime(
            JavaSe8\Time::zonedDateTime($zonedDateTime)->setTimezone(new DateTimeZone('UTC')),
        );
    }

    /**
     * @param ($format is null ? DateTimeInterface : string) $utcDateTime
     * @param ($format is null ? DateTimeInterface : string) $localDateTime
     *
     * @return ZonedDateTime
     *
     * @throws Exception\ZonedDateTimePersistenceCouldNotComputeZonedDateTime
     */
    public static function computeZonedDateTime(
        DateTimeInterface|string $utcDateTime,
        DateTimeInterface|string $localDateTime,
        string|null $format = null,
    ): DateTimeImmutable {
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
