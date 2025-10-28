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
    /**
     * @return LocalDateTime
     */
    public static function computeUtcCompanion(
        DateTimeInterface $zonedDateTime,
    ): DateTimeImmutable {
        return JavaSe8\Time::toLocalDateTime(
            JavaSe8\Time::zonedDateTime($zonedDateTime)->setTimezone(new DateTimeZone('UTC')),
        );
    }

    /**
     * @param ($format is null ? DateTimeInterface : string) $localDateTime
     * @param ($format is null ? DateTimeInterface : string) $utcCompanion
     *
     * @return ZonedDateTime
     */
    public static function computeZonedDateTime(
        DateTimeInterface|string $localDateTime,
        DateTimeInterface|string $utcCompanion,
        string|null $format = null,
    ): DateTimeImmutable {
        if ($format === null) {
            $localDateTime = JavaSe8\Time::localDateTime($localDateTime);
            $utcCompanion = JavaSe8\Time::localDateTime($utcCompanion);
        } else {
            $localDateTime = DateTimeUtils::parseAsLocalDateTime($localDateTime, $format);
            $utcCompanion = DateTimeUtils::parseAsLocalDateTime($utcCompanion, $format);
        }
        $offset = DateTimeUtils::secondsBetween($utcCompanion, $localDateTime);
        return DateTimeUtils::asUtcInstantAtOffset($utcCompanion, $offset);
    }
}
