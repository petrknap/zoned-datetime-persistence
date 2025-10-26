<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

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
     * @param ($format is null ? DateTimeInterface : string) $dateTime
     *
     * @return ZonedDateTime
     */
    public static function parse(
        DateTimeInterface|string $dateTime,
        string|null $format = null,
    ): DateTimeImmutable {
        if (is_string($dateTime)) {
            if ($format === null) {
                throw new InvalidArgumentException('Missing $format');
            }
            $dateTime = DateTimeImmutable::createFromFormat($format, $dateTime)
                ?: throw new Exception\DateTimeUtilsCouldNotParse(
                    dateTime: $dateTime,
                    format: $format,
                )
            ;
        }
        return JavaSe8\Time::zonedDateTime($dateTime);
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
