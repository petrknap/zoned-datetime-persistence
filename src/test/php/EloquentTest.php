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

    public function test_loads_saved_model(): void
    {
        self::prepareManager();

        $localDateTime = Carbon::createFromInterface($this->localDateTime);
        $utcDateTime = Carbon::createFromInterface($this->utcDateTime);
        $zonedDateTime = Carbon::createFromInterface($this->zonedDateTime);
        $noteDateFormat = (new Some\NoteModel())->getDateFormat();

        $createdNote = new Some\NoteModel();
        $createdNote->content = 'test';
        $createdNote->created_at = $zonedDateTime;
        $createdNote->created_at_utc = $utcDateTime;
        $createdNote->save();
        $loadedNote = Some\NoteModel::query()
            ->where('content', '=', 'test')
            // ---------------------------------------------------------------------------------------------------------
            // Case: UTC date-time with local date-time
            ->where('created_at__utc', '=', $utcDateTime->format($noteDateFormat))
            ->where('created_at__local', '=', $localDateTime->format($noteDateFormat))
            // ---------------------------------------------------------------------------------------------------------
            // Case: nullable attribute
            ->whereNull('deleted_at__utc')
            ->whereNull('deleted_at__local')
            // ---------------------------------------------------------------------------------------------------------
            // Case: UTC date-time cast
            ->where('created_at_utc', '=', $utcDateTime->format($noteDateFormat))
            // ---------------------------------------------------------------------------------------------------------
            // Case: casted nullable
            ->whereNull('deleted_at_utc')
            // ---------------------------------------------------------------------------------------------------------
            ->firstOrFail();

        // -------------------------------------------------------------------------------------------------------------
        // Case: UTC date-time with local date-time
        self::assertDateTimeEquals(
            $zonedDateTime,
            $createdNote->created_at,
            'Unexpected createdNote.created_at',
        );
        self::assertDateTimeEquals(
            $zonedDateTime,
            $loadedNote->created_at,
            'Unexpected loadedNote.created_at',
        );
        // -------------------------------------------------------------------------------------------------------------
        // Case: nullable attribute
        self::assertNull(
            $createdNote->deleted_at,
            'Unexpected createdNote.deleted_at',
        );
        self::assertNull(
            $loadedNote->deleted_at,
            'Unexpected createdNote.deleted_at',
        );
        // -------------------------------------------------------------------------------------------------------------
        // Case: UTC date-time cast
        self::assertDateTimeEquals(
            $utcDateTime,
            $createdNote->created_at_utc,
            'Unexpected createdNote.created_at_utc',
        );
        self::assertDateTimeEquals(
            $utcDateTime,
            $loadedNote->created_at_utc,
            'Unexpected loadedNote.created_at_utc',
        );
        // -------------------------------------------------------------------------------------------------------------
        // Case: casted nullable
        self::assertNull(
            $createdNote->deleted_at_utc,
            'Unexpected createdNote.deleted_at_utc',
        );
        self::assertNull(
            $loadedNote->deleted_at_utc,
            'Unexpected loadedNote.deleted_at_utc',
        );
        // -------------------------------------------------------------------------------------------------------------
    }
}
