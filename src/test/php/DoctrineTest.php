<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;

final class DoctrineTest extends TestCase
{
    public static function prepareEntityManager(): EntityManager
    {
        if (!Type::hasType(UtcDateTimeType::NAME)) {
            Type::addType(UtcDateTimeType::NAME, UtcDateTimeType::class);
        }

        $config = ORMSetup::createAttributeMetadataConfiguration([
            __DIR__,
        ], isDevMode: true);

        $entityManager = new EntityManager(
            DriverManager::getConnection([
                'driver' => 'pdo_sqlite',
                'memory' => true,
            ], $config),
            $config,
        );

        (new SchemaTool($entityManager))->createSchema([
            $entityManager->getClassMetadata(Some\Note::class),
        ]);

        return $entityManager;
    }

    public function test_custom_mapping_type(): void
    {
        $entityManager = self::prepareEntityManager();

        $createdNote = new Some\Note($this->zonedDateTime, 'test');
        $entityManager->persist($createdNote);
        $entityManager->flush();
        $entityManager->clear();
        $loadedNote = $entityManager
            ->createQuery(
                'SELECT note FROM ' . Some\Note::class . ' note' .
                " WHERE note.content = 'test'" .
                ' AND note.createdAtUtc = :utc' .
                ' AND note.createdAtUtc != :zoned' . // @todo wait until Doctrine ORM fixes this issue and change it to `=`
                ' AND note.deletedAtUtc IS NULL',
            )
            ->setParameter('utc', $this->utcDateTime)
            ->setParameter('zoned', $this->zonedDateTime)
            ->getSingleResult();

        self::assertDateTimeEquals(
            $this->utcDateTime,
            $createdNote->createdAtUtc,
            'Unexpected createdNote.createdAtUtc',
        );
        self::assertNull(
            $createdNote->deletedAtUtc,
            'Unexpected createdNote.deletedAtUtc',
        );
        self::assertDateTimeEquals(
            $this->utcDateTime,
            $loadedNote->createdAtUtc,
            'Unexpected loadedNote.createdAtUtc',
        );
        self::assertNull(
            $loadedNote->deletedAtUtc,
            'Unexpected loadedNote.deletedAtUtc',
        );
    }

    public function test_embeddables(): void
    {
        $entityManager = self::prepareEntityManager();

        $createdNote = new Some\Note($this->zonedDateTime, 'test');
        $entityManager->persist($createdNote);
        $entityManager->flush();
        $entityManager->clear();
        $loadedNote = $entityManager
            ->createQuery(
                'SELECT note FROM ' . Some\Note::class . ' note' .
                    " WHERE note.content = 'test'" .
                    ' AND note.createdAt.utc = :utc AND note.createdAt.local = :local' .
                    ' AND note.createdAt2.utc = :utc AND note.createdAt2.timezone = :timezone' .
                    ' AND note.deletedAt.utc IS NULL',
            )
            ->setParameter('utc', JavaSe8\Time::toLocalDateTime($this->utcDateTime))
            ->setParameter('local', $this->localDateTime)
            ->setParameter('timezone', $this->zonedDateTime->getTimezone()->getName())
            ->getSingleResult();

        self::assertDateTimeEquals(
            $this->zonedDateTime,
            $createdNote->getCreatedAt(),
            'Unexpected createdNote.getCreatedAt()',
        );
        self::assertNull(
            $createdNote->getDeletedAt(),
            'Unexpected createdNote.getDeletedAt()',
        );
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            $loadedNote->getCreatedAt(),
            'Unexpected loadedNote.getCreatedAt()',
        );
        self::assertNull(
            $loadedNote->getDeletedAt(),
            'Unexpected loadedNote.getDeletedAt()',
        );
    }

    public function test_Doctrine_ORM_can_be_optional_requirement(): void
    {
        self::assertNotNull(
            new #[NonExistend\ORM\Embeddable] class () { // @phpstan-ignore-line
                #[NonExistend\ORM\Column(nullable: true)] // @phpstan-ignore-line
                public int $id = 0;
            },
        );
    }
}
