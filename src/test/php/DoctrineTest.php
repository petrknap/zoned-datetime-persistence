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

    public function testEmbeddable(): void
    {
        $entityManager = self::prepareEntityManager();
        $createdNote = new Some\Note(
            createdAt: $this->zonedDateTime,
            content: 'test',
        );
        $entityManager->persist($createdNote);
        $entityManager->flush();
        $entityManager->clear();

        $loadedNote = $entityManager->find(Some\Note::class, $createdNote->getId());

        self::assertDateTimeEquals(
            $this->zonedDateTime,
            $loadedNote->getCreatedAt(),
        );
    }
}
