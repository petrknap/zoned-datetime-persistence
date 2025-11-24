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

    public function test_loads_persisted_entity(): void
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
                // -----------------------------------------------------------------------------------------------------
                // Case: UTC date-time with local date-time
                ' AND note.createdAt.utc = :localUtc AND note.createdAt.local = :local' .
                // -----------------------------------------------------------------------------------------------------
                // Case: UTC date-time with timezone identifier
                ' AND note.createdAt2.utc = :localUtc AND note.createdAt2.timezone = :timezone' .
                // -----------------------------------------------------------------------------------------------------
                // Case: nullable embeddable
                ' AND note.deletedAt.utc IS NULL' .
                // -----------------------------------------------------------------------------------------------------
                // Case: UTC date-time type
                ' AND note.createdAtUtc = :zonedUtc ' .
                ' AND note.createdAtUtc = :zoned' .
                ' AND note.createdAtUtc != :untypedZoned' . // @todo wait until Doctrine ORM fixes this issue
                // -----------------------------------------------------------------------------------------------------
                // Case: typed nullable
                ' AND note.deletedAtUtc IS NULL' .
                // -----------------------------------------------------------------------------------------------------
                ' ',
            )
            ->setParameter('localUtc', JavaSe8\Time::toLocalDateTime($this->utcDateTime))
            ->setParameter('local', $this->localDateTime)
            ->setParameter('timezone', $this->zonedDateTime->getTimezone()->getName())
            ->setParameter('zonedUtc', $this->utcDateTime)
            ->setParameter('zoned', $this->zonedDateTime, UtcDateTimeType::NAME)
            ->setParameter('untypedZoned', $this->zonedDateTime)
            ->getSingleResult();

        // -------------------------------------------------------------------------------------------------------------
        // Case: UTC date-time with local date-time
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            $createdNote->getCreatedAt(),
            'Unexpected createdNote.getCreatedAt()',
        );
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            $loadedNote->getCreatedAt(),
            'Unexpected loadedNote.getCreatedAt()',
        );
        // ---------------------------------------------------------------------------------------------------------
        // Case: UTC date-time with timezone identifier
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            $createdNote->getCreatedAt2(),
            'Unexpected createdNote.getCreatedAt2()',
        );
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            $loadedNote->getCreatedAt2(),
            'Unexpected loadedNote.getCreatedAt2()',
        );
        // ---------------------------------------------------------------------------------------------------------
        // Case: nullable embeddable
        self::assertNull(
            $createdNote->getDeletedAt(),
            'Unexpected createdNote.getDeletedAt()',
        );
        self::assertNull(
            $loadedNote->getDeletedAt(),
            'Unexpected loadedNote.getDeletedAt()',
        );
        // -------------------------------------------------------------------------------------------------------------
        // Case: UTC date-time type
        self::assertDateTimeEquals(
            $this->utcDateTime,
            $createdNote->createdAtUtc,
            'Unexpected createdNote.createdAtUtc',
        );
        self::assertDateTimeEquals(
            $this->utcDateTime,
            $loadedNote->createdAtUtc,
            'Unexpected loadedNote.createdAtUtc',
        );
        // -------------------------------------------------------------------------------------------------------------
        // Case: typed nullable
        self::assertNull(
            $createdNote->deletedAtUtc,
            'Unexpected createdNote.deletedAtUtc',
        );
        self::assertNull(
            $loadedNote->deletedAtUtc,
            'Unexpected loadedNote.deletedAtUtc',
        );
        // -------------------------------------------------------------------------------------------------------------
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
