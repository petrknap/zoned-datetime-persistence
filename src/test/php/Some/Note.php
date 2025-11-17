<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence\Some;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use PetrKnap\ZonedDateTimePersistence\UtcWithLocal;
use PetrKnap\ZonedDateTimePersistence\UtcWithTimezone;
use PetrKnap\ZonedDateTimePersistence\UtcDateTimeType;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'notes')]
final class Note
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    protected int|null $id = null;

    /**
     * Example: utc date-time with local date-time
     */
    #[ORM\Embedded(columnPrefix: 'created_at__')]
    protected UtcWithLocal $createdAt;

    /**
     * Example: utc date-time with timezone identifier
     */
    #[ORM\Embedded(columnPrefix: 'created_at_2__')]
    protected UtcWithTimezone $createdAt2;

    /**
     * Example: nullable embeddable
     */
    #[ORM\Embedded]
    protected UtcWithLocal|null $updatedAt = null;

    /**
     * Example: utc date-time
     */
    #[ORM\Column(name: 'created_at_utc', type: UtcDateTimeType::NAME, nullable: true)] // nullable for testing purposes only
    public DateTimeInterface $createdAtUtc;

    /**
     * Example: nullable type
     */
    #[ORM\Column(name: 'updated_at_utc', type: UtcDateTimeType::NAME, nullable: true)]
    public DateTimeInterface|null $updatedAtUtc = null;

    public function __construct(
        DateTimeInterface $createdAt,
        #[ORM\Column(name: 'content', nullable: false)]
        protected string $content,
    ) {
        $this->createdAt = new UtcWithLocal($createdAt);
        $this->createdAt2 = new UtcWithTimezone($createdAt);
        $this->createdAtUtc = DateTimeImmutable::createFromInterface($createdAt)->setTimezone(new DateTimeZone('UTC'));
    }

    /**
     * @internal this is an event listener
     */
    #[ORM\PostLoad]
    public function fixNullables(): void
    {
        $this->updatedAt = $this->updatedAt?->asNullable();
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt->toZonedDateTime();
    }

    public function getUpdatedAt(): DateTimeInterface|null
    {
        return $this->updatedAt?->toZonedDateTime();
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
        $this->updatedAt = new UtcWithLocal(new DateTimeImmutable('now'));
        $this->updatedAtUtc = new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }
}
