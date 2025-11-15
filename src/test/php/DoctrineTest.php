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
        $createdNote = new Some\Note($this->zonedDateTime, '');
        $entityManager->persist($createdNote);
        $entityManager->flush();
        $entityManager->clear();

        $loadedNote = $entityManager
            ->createQuery(
                'SELECT note FROM ' . Some\Note::class . ' note' .
                    ' WHERE note.createdAt.utc = :utc AND note.createdAt.local = :local',
            )
            ->setParameter('utc', JavaSe8\Time::toLocalDateTime($this->utcDateTime))
            ->setParameter('local', $this->localDateTime)
            ->getSingleResult();

        self::assertDateTimeEquals(
            $this->zonedDateTime,
            $loadedNote->getCreatedAt(),
        );
    }
}
