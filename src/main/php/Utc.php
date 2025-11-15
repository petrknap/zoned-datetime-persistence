<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 */
#[ORM\MappedSuperclass]
abstract class Utc
{
    /**
     * @var LocalDateTime
     */
    #[ORM\Column(type: 'datetime_immutable')]
    protected readonly DateTimeImmutable $utc;

    protected function __construct(
        DateTimeInterface $zonedDateTime,
    ) {
        $this->utc = ZonedDateTimePersistence::computeUtcDateTime(JavaSe8\Time::zonedDateTime($zonedDateTime));
    }

    /**
     * @return ($format is false ? LocalDateTime : string)
     */
    public function getUtcDateTime(string|false $format = false): DateTimeImmutable|string
    {
        return $format ? $this->utc->format($format) : $this->utc;
    }

    /**
     * @return ZonedDateTime
     */
    abstract public function toZonedDateTime(): DateTimeImmutable;
}
