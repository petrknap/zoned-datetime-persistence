<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence\Some;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use PetrKnap\ZonedDateTimePersistence\LocalDateTimeWithUtcCompanion;

#[ORM\Entity]
#[ORM\Table(name: 'notes')]
final class Note
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    protected int|null $id = null;
    #[ORM\Embedded(columnPrefix: 'created_at__')]
    protected LocalDateTimeWithUtcCompanion $createdAt;

    public function __construct(
        DateTimeInterface $createdAt,
        #[ORM\Column(name: 'content')]
        protected string $content,
    ) {
        $this->createdAt = new LocalDateTimeWithUtcCompanion($createdAt);
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt->toZonedDateTime();
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
