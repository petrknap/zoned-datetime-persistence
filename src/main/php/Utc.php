<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * @internal base
 *
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 */
#[ORM\MappedSuperclass]
abstract class Utc
{
    /**
     * @var LocalDateTime|null
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    protected readonly DateTimeImmutable|null $utc;

    protected function __construct(
        DateTimeInterface $zonedDateTime,
    ) {
        $this->utc = ZonedDateTimePersistence::computeUtcDateTime(JavaSe8\Time::zonedDateTime($zonedDateTime));
    }

    public function asNullable(): static|null
    {
        return $this->utc === null ? null : $this;
    }

    /**
     * @return ($format is false ? LocalDateTime : string)
     */
    public function getUtcDateTime(string|false $format = false): DateTimeImmutable|string
    {
        if ($this->utc === null) {
            $this->thisInstanceShouldBeNull();
        }
        return $format ? $this->utc->format($format) : $this->utc;
    }

    /**
     * @return ZonedDateTime
     */
    abstract public function toZonedDateTime(): DateTimeImmutable;

    protected function thisInstanceShouldBeNull(): never
    {
        throw new LogicException(get_class($this) . ' instance was created without data, call asNullable() method or adjust your object–relational mapping');
    }
}
