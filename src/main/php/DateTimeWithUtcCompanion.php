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
 * @phpstan-type Constructed array{0: ZonedDateTime, 1: ZonedDateTime}
 */
final class DateTimeWithUtcCompanion
{
    /**
     * @var ZonedDateTime
     */
    public readonly DateTimeImmutable $dateTime;
    /**
     * @var ZonedDateTime
     */
    public readonly DateTimeImmutable $utcCompanion;

    public function __construct(
        DateTimeInterface $dateTime,
        DateTimeInterface|null $utcCompanion = null,
    ) {
        if ($utcCompanion === null) {
            [$this->dateTime, $this->utcCompanion] = self::constructFromZonedDateTime($dateTime);
        } elseif ($dateTime->getOffset() === $utcCompanion->getOffset()) {
            [$this->dateTime, $this->utcCompanion] = self::constructFromLocalDateTimes($dateTime, $utcCompanion);
        } else {
            [$this->dateTime, $this->utcCompanion] = self::constructFromZonedDateTimes($dateTime, $utcCompanion);
        }
    }

    /**
     * @return Constructed
     */
    private function constructFromLocalDateTimes(DateTimeInterface $dateTime, DateTimeInterface $utcCompanion): array
    {
        return self::constructFromZonedDateTime(
            ZonedDateTimePersistence::computeZonedDateTime($dateTime, $utcCompanion),
        );
    }

    /**
     * @return Constructed
     */
    private function constructFromZonedDateTime(DateTimeInterface $dateTime): array
    {
        $zonedDateTime = JavaSe8\Time::zonedDateTime($dateTime);

        return self::constructFromZonedDateTimes(
            $zonedDateTime,
            ZonedDateTimePersistence::computeUtcCompanion($zonedDateTime),
        );
    }

    /**
     * @return Constructed
     */
    private function constructFromZonedDateTimes(DateTimeInterface $dateTime, DateTimeInterface $utcCompanion): array
    {
        $utcCompanionOffset = $utcCompanion->getOffset();
        $dateTimeToUtcCompanionDifference = DateTimeUtils::difference($dateTime, $utcCompanion);
        if ($utcCompanionOffset !== 0) {
            throw new InvalidArgumentException(sprintf(
                '$utcCompanion must have zero offset, got %d seconds',
                $utcCompanionOffset,
            ));
        } elseif ($dateTimeToUtcCompanionDifference !== 0) {
            throw new InvalidArgumentException(sprintf(
                '$dateTime and $utcCompanion must have zero difference, got %d seconds',
                $dateTimeToUtcCompanionDifference,
            ));
        }

        return [
            JavaSe8\Time::zonedDateTime($dateTime),
            JavaSe8\Time::zonedDateTime($utcCompanion),
        ];
    }
}
