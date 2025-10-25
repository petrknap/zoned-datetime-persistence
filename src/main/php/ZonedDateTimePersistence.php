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
     * @return ZonedDateTime
     */
    public static function computeUtcCompanion(
        DateTimeInterface $zonedDateTime,
    ): DateTimeImmutable {
        return JavaSe8\Time::zonedDateTime($zonedDateTime)
            ->setTimezone(new DateTimeZone('UTC'));
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
        $localDateTime = JavaSe8\Time::toLocalDateTime(
            DateTimeUtils::parse($localDateTime, $format),
        );
        $utcCompanion = JavaSe8\Time::toLocalDateTime(
            DateTimeUtils::parse($utcCompanion, $format),
        );
        $offset = DateTimeUtils::difference($localDateTime, $utcCompanion);
        return DateTimeUtils::atOffset($utcCompanion, $offset);
    }
}
