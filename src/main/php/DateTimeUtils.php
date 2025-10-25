<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use RuntimeException;

/**
 * @internal helper
 *
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 */
final class DateTimeUtils
{
    /**
     * @param LocalDateTime $localDateTime
     * @param int $offset seconds
     *
     * @return ZonedDateTime
     */
    public static function atOffset(
        DateTimeImmutable $localDateTime,
        int $offset,
    ): DateTimeImmutable {
        /** @var ZonedDateTime */
        return $localDateTime->setTimezone(self::offsetTimezone($offset));
    }

    /**
     * @param ($format is null ? DateTimeInterface : string) $zonedDateTime
     *
     * @return ZonedDateTime
     */
    public static function parse(
        DateTimeInterface|string $zonedDateTime,
        string|null $format = null,
    ): DateTimeImmutable {
        $zonedDateTime = $format === null
            ? $zonedDateTime
            : DateTimeImmutable::createFromFormat($format, $zonedDateTime)
        ;
        if ($zonedDateTime === false) {
            throw new RuntimeException('Could not parse $zonedDateTime as $format');
        }
        return JavaSe8\Time::zonedDateTime($zonedDateTime);
    }

    /**
     * @return int seconds
     */
    public static function difference(
        DateTimeInterface $a,
        DateTimeInterface $b,
    ): int {
        return $a->getTimestamp() - $b->getTimestamp();
    }

    public static function offsetTimezone(int $offset): DateTimeZone
    {
        return new DateTimeZone(sprintf(
            '%s%02d:%02d',
            $offset >= 0 ? '+' : '-',
            abs(intdiv($offset, 3600)),
            abs(($offset % 3600) / 60),
        ));
    }
}
