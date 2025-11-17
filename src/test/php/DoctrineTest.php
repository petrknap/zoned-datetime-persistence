<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;

final class DoctrineTest extends TestCase
{
    public static function prepareEntityManager(): EntityManager
    {
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

    public function test_embeddable(): void
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
                    ' AND note.updatedAt.utc IS NULL',
            )
            ->setParameter('utc', JavaSe8\Time::toLocalDateTime($this->utcDateTime))
            ->setParameter('local', $this->localDateTime)
            ->getSingleResult();

        self::assertDateTimeEquals(
            $this->zonedDateTime,
            $createdNote->getCreatedAt(),
            'Incorrect createdNote.createdAt',
        );
        self::assertNull(
            $createdNote->getUpdatedAt(),
            'Incorrect createdNote.updatedAt',
        );
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            $loadedNote->getCreatedAt(),
            'Incorrect loadedNote.createdAt',
        );
        self::assertNull(
            $loadedNote->getUpdatedAt(),
            'Incorrect loadedNote.updatedAt',
        );
    }
}
