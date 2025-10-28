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
    public const TIMEZONE_LESS_FORMAT = 'Y-m-d H:i:s.u';
    private const LOCAL_OFFSET = 0;

    /**
     * @param LocalDateTime $localDateTime
     *
     * @return ZonedDateTime
     */
    public static function toInstant(DateTimeImmutable $localDateTime, int $offset): DateTimeImmutable
    {
        /** @var ZonedDateTime */
        return DateTimeImmutable::createFromFormat(
            self::TIMEZONE_LESS_FORMAT,
            $localDateTime->format(self::TIMEZONE_LESS_FORMAT),
            self::zoneOffset($offset),
        );
    }

    /**
     * @param ZonedDateTime $zonedDateTime
     *
     * @return LocalDateTime
     */
    public static function toLocalDateTime(DateTimeImmutable $zonedDateTime): DateTimeImmutable
    {
        /** @var LocalDateTime */
        return $zonedDateTime->getOffset() === self::LOCAL_OFFSET
            ? $zonedDateTime // this is performance hack - input has correct offset
            : DateTimeImmutable::createFromFormat(
                self::TIMEZONE_LESS_FORMAT,
                $zonedDateTime->format(self::TIMEZONE_LESS_FORMAT),
                self::zoneOffset(self::LOCAL_OFFSET),
            )
        ;
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

    public static function zoneOffset(int $ofTotalSeconds): DateTimeZone
    {
        return new DateTimeZone(sprintf(
            '%s%02d:%02d',
            $ofTotalSeconds >= 0 ? '+' : '-',
            abs(intdiv($ofTotalSeconds, 3600)),
            abs(($ofTotalSeconds % 3600) / 60),
        ));
    }
}
