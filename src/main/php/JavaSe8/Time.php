<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence\JavaSe8;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * @phpstan-type LocalDateTime DateTimeImmutable&object{_local: null}
 * @phpstan-type ZonedDateTime DateTimeImmutable&object{_zoned: null}
 */
final class Time
{
    public const LOCAL_DATETIME_OFFSET = 0;
    private const LOCAL_DATETIME_PATTERN = 'Y-m-d H:i:s.u';

    private function __construct()
    {
    }

    /**
     * @param LocalDateTime $localDateTime
     *
     * @return ZonedDateTime
     */
    public static function toInstant(DateTimeImmutable $localDateTime, int $offset): DateTimeImmutable
    {
        /** @var ZonedDateTime */
        return self::overrideTimezone($localDateTime, $offset);
    }

    /**
     * @param ZonedDateTime $zonedDateTime
     *
     * @return LocalDateTime
     */
    public static function toLocalDateTime(DateTimeImmutable $zonedDateTime): DateTimeImmutable
    {
        /** @var LocalDateTime */
        return self::overrideTimezone($zonedDateTime, self::LOCAL_DATETIME_OFFSET);
    }

    /**
     * `LocalDateTime` factory
     *
     * @return LocalDateTime
     */
    public static function localDateTime(DateTimeInterface $dateTime): DateTimeImmutable
    {
        return self::toLocalDateTime(self::zonedDateTime($dateTime));
    }

    /**
     * `ZonedDateTime` factory
     *
     * @return ZonedDateTime
     */
    public static function zonedDateTime(DateTimeInterface $dateTime): DateTimeImmutable
    {
        /** @var ZonedDateTime */
        return $dateTime instanceof DateTimeImmutable
            ? $dateTime // this is performance hack - input has correct type
            : DateTimeImmutable::createFromInterface($dateTime)
        ;
    }

    /**
     * `ZoneOffset` factory
     *
     * @return DateTimeZone
     */
    public static function zoneOffset(int $ofTotalSeconds): DateTimeZone
    {
        return new DateTimeZone(sprintf(
            '%s%02d:%02d',
            $ofTotalSeconds >= 0 ? '+' : '-',
            abs(intdiv($ofTotalSeconds, 3600)),
            abs(($ofTotalSeconds % 3600) / 60),
        ));
    }

    private static function overrideTimezone(DateTimeImmutable $dateTime, int $offset): DateTimeImmutable
    {
        /** @var DateTimeImmutable */
        return $dateTime->getOffset() === $offset
            ? $dateTime // this is performance hack - input has correct offset
            : DateTimeImmutable::createFromFormat(
                self::LOCAL_DATETIME_PATTERN,
                $dateTime->format(self::LOCAL_DATETIME_PATTERN),
                self::zoneOffset($offset),
            );
    }
}
