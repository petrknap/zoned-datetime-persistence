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
    #[ORM\Embedded(columnPrefix: 'deleted_at__')]
    protected UtcWithLocal|null $deletedAt = null;

    /**
     * Example: utc date-time
     */
    #[ORM\Column(name: 'created_at_utc', type: UtcDateTimeType::NAME, options: ['default' => 'CURRENT_TIMESTAMP'])]
    public DateTimeInterface $createdAtUtc;

    /**
     * Example: nullable type
     */
    #[ORM\Column(name: 'deleted_at_utc', type: UtcDateTimeType::NAME, nullable: true)]
    public DateTimeInterface|null $deletedAtUtc = null;

    public function __construct(
        DateTimeImmutable $createdAt,
        #[ORM\Column(name: 'content', nullable: false)]
        protected string $content,
    ) {
        $this->createdAt = new UtcWithLocal($createdAt);
        $this->createdAt2 = new UtcWithTimezone($createdAt);
        $this->createdAtUtc = $createdAt->setTimezone(new DateTimeZone('UTC'));
    }

    /**
     * @internal this is an event listener
     */
    #[ORM\PostLoad]
    public function fixNullables(): void
    {
        $this->deletedAt = $this->deletedAt?->asNullable();
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt->toZonedDateTime();
    }

    public function getDeletedAt(): DateTimeInterface|null
    {
        return $this->deletedAt?->toZonedDateTime();
    }
}
