<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

/**
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 *
 * @phpstan-type Constructed array{0: LocalDateTime, 1: LocalDateTime}
 */
final class LocalDateTimeWithUtcCompanion
{
    /**
     * @var LocalDateTime
     */
    public readonly DateTimeImmutable $localDateTime;
    /**
     * @var LocalDateTime
     */
    public readonly DateTimeImmutable $utcCompanion;

    public function __construct(
        DateTimeInterface $dateTime,
        DateTimeInterface|null $utcCompanion = null,
    ) {
        if ($utcCompanion === null) {
            [$this->localDateTime, $this->utcCompanion] = self::constructFromZonedDateTime($dateTime);
        } elseif ($dateTime->getOffset() === $utcCompanion->getOffset()) {
            [$this->localDateTime, $this->utcCompanion] = self::constructFromLocalDateTimes($dateTime, $utcCompanion);
        } else {
            throw new InvalidArgumentException('Arguments must be zoned $dateTime, or local $dateTime and $utcCompanion');
        }
    }

    /**
     * @return ZonedDateTime
     */
    public function toZonedDateTime(): DateTimeImmutable
    {
        return ZonedDateTimePersistence::computeZonedDateTime($this->localDateTime, $this->utcCompanion);
    }

    /**
     * @return Constructed
     */
    private function constructFromLocalDateTimes(DateTimeInterface $dateTime, DateTimeInterface $utcCompanion): array
    {
        return [
            JavaSe8\Time::localDateTime($dateTime),
            JavaSe8\Time::localDateTime($utcCompanion),
        ];
    }

    /**
     * @return Constructed
     */
    private function constructFromZonedDateTime(DateTimeInterface $dateTime): array
    {
        $zonedDateTime = JavaSe8\Time::zonedDateTime($dateTime);

        return self::constructFromLocalDateTimes(
            JavaSe8\Time::toLocalDateTime($zonedDateTime),
            ZonedDateTimePersistence::computeUtcCompanion($zonedDateTime),
        );
    }
}
