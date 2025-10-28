<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

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
    public static function parseAsLocalDateTime(string $text, string $pattern): DateTimeImmutable
    {
        return JavaSe8\Time::localDateTime(
            DateTimeImmutable::createFromFormat($pattern, $text)
                ?: throw new Exception\DateTimeUtilsCouldNotParseAsLocalDateTime(
                    text: $text,
                    pattern: $pattern,
                ),
        );
    }

    public static function secondsBetween(
        DateTimeInterface $startInclusive,
        DateTimeInterface $endExclusive,
    ): int {
        return $endExclusive->getTimestamp() - $startInclusive->getTimestamp();
    }
}
