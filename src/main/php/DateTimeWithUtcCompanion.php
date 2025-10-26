<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use LogicException;

/**
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 *
 * @phpstan-type ConstructorArgs array{0: ZonedDateTime, 1: ZonedDateTime}
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
            [$dateTime, $utcCompanion] = self::createArgsFromZonedDateTime($dateTime);
        } elseif ($dateTime->getOffset() === $utcCompanion->getOffset()) {
            [$dateTime, $utcCompanion] = self::createArgsFromLocalDateTimes($dateTime, $utcCompanion);
        } else {
            [$dateTime, $utcCompanion] = self::createArgsFromZonedDateTimes($dateTime, $utcCompanion);
        }

        $this->dateTime = $dateTime;
        $this->utcCompanion = $utcCompanion;

        if ($this->utcCompanion->getOffset() !== 0) {
            throw new LogicException('$utcCompanion must have zero offset');
        } elseif (DateTimeUtils::difference($this->dateTime, $this->utcCompanion) !== 0) {
            throw new LogicException('$dateTime and $utcCompanion must have zero difference');
        }
    }

    /**
     * @return ConstructorArgs
     */
    private function createArgsFromLocalDateTimes(DateTimeInterface $dateTime, DateTimeInterface $utcCompanion): array
    {
        return self::createArgsFromZonedDateTime(
            ZonedDateTimePersistence::computeZonedDateTime($dateTime, $utcCompanion),
        );
    }

    /**
     * @return ConstructorArgs
     */
    private function createArgsFromZonedDateTime(DateTimeInterface $dateTime): array
    {
        $zonedDateTime = JavaSe8\Time::zonedDateTime($dateTime);

        return [
            $zonedDateTime,
            ZonedDateTimePersistence::computeUtcCompanion($zonedDateTime),
        ];
    }

    /**
     * @return ConstructorArgs
     */
    private function createArgsFromZonedDateTimes(DateTimeInterface $dateTime, DateTimeInterface $utcCompanion): array
    {
        return [
            JavaSe8\Time::zonedDateTime($dateTime),
            JavaSe8\Time::zonedDateTime($utcCompanion),
        ];
    }
}
