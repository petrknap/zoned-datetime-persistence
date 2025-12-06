<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * @internal helper
 *
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 */
final class DateTimeUtils
{
    private function __construct()
    {
    }

    /**
     * @param LocalDateTime $localDateTime
     * @param int $offset seconds
     *
     * @return ZonedDateTime
     */
    public static function asUtcInstantAtOffset(
        DateTimeImmutable $localDateTime,
        int $offset,
    ): DateTimeImmutable {
        return JavaSe8\Time::toInstant($localDateTime, 0)
            ->setTimezone(JavaSe8\Time::zoneOffset($offset));
    }

    /**
     * @return LocalDateTime
     *
     * @throws Exception\DateTimeUtilsCouldNotParseAsLocalDateTime
     */
    public static function parseAsLocalDateTime(string $datetime, string $format): DateTimeImmutable
    {
        return JavaSe8\Time::localDateTime(
            DateTimeImmutable::createFromFormat($format, $datetime)
                ?: throw new Exception\DateTimeUtilsCouldNotParseAsLocalDateTime($datetime, $format, DateTimeImmutable::class),
        );
    }

    public static function secondsBetween(
        DateTimeInterface $startInclusive,
        DateTimeInterface $endExclusive,
    ): int {
        return $endExclusive->getTimestamp() - $startInclusive->getTimestamp();
    }
}
