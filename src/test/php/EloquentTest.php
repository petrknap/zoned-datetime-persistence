<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;
use Illuminate\Support\Carbon;
use PDO;

final class EloquentTest extends TestCase
{
    public static function prepareManager(): Manager
    {
        /** @var PDO $pdo */
        $pdo = DoctrineTest::prepareEntityManager()
            ->getConnection()
            ->getNativeConnection();

        $manager = new Manager();
        $manager->addConnection([]);
        $manager->getDatabaseManager()
            ->extend('default', static fn (): Connection => new Connection($pdo));
        $manager->setAsGlobal();
        $manager->bootEloquent();

        return $manager;
    }

    public function test_casts_attribute(): void
    {
        self::prepareManager();
        $utcDateTime = Carbon::createFromInterface($this->utcDateTime);
        $noteDateFormat = (new Some\NoteModel())->getDateFormat();

        $createdNote = new Some\NoteModel();
        $createdNote->content = 'test';
        $createdNote->created_at_utc = $utcDateTime;
        $createdNote->save();
        $loadedNote = Some\NoteModel::query()
            ->where('content', '=', 'test')
            ->where('created_at_utc', '=', $utcDateTime->format($noteDateFormat))
            ->whereNull('deleted_at_utc')
            ->firstOrFail();

        self::assertDateTimeEquals(
            $utcDateTime,
            $createdNote->created_at_utc,
            'Unexpected createdNote.created_at_utc',
        );
        self::assertNull(
            $createdNote->deleted_at_utc,
            'Unexpected createdNote.deleted_at_utc',
        );
        self::assertDateTimeEquals(
            $utcDateTime,
            $loadedNote->created_at_utc,
            'Unexpected loadedNote.created_at_utc',
        );
        self::assertNull(
            $loadedNote->deleted_at_utc,
            'Unexpected loadedNote.deleted_at_utc',
        );
    }
}
