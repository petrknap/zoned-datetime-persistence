<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase as Base;

/**
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 */
abstract class TestCase extends Base
{
    protected const LOCAL_DATETIME = '2025-10-25 16:05';
    protected const LOCAL_DATETIME_PATTERN = 'Y-m-d H:i';
    protected const ZONED_DATETIME = '2025-10-25 16:05 +02:00';
    protected const ZONED_DATETIME_OFFSET = 7200;
    protected const ZONED_DATETIME_PATTERN = 'Y-m-d H:i P';

    /**
     * @var LocalDateTime
     */
    protected DateTimeImmutable $localDateTime;
    /**
     * @var ZonedDateTime
     */
    protected DateTimeImmutable $zonedDateTime;
    /**
     * @var ZonedDateTime
     */
    protected DateTimeImmutable $utcDateTime;

    protected function setUp(): void
    {
        parent::setUp();

        $this->localDateTime = JavaSe8\Time::localDateTime(new DateTimeImmutable(self::LOCAL_DATETIME));
        $this->zonedDateTime = JavaSe8\Time::zonedDateTime(new DateTimeImmutable(self::ZONED_DATETIME));
        $this->utcDateTime = $this->zonedDateTime->setTimezone(new DateTimeZone('UTC'));
    }

    /**
     * @param LocalDateTime|ZonedDateTime $expected
     * @param LocalDateTime|ZonedDateTime $actual
     */
    protected static function assertDateTimeEquals(
        DateTimeImmutable $expected,
        DateTimeImmutable $actual,
        string $message = '',
    ): void {
        self::assertSame(
            $expected->format(self::ZONED_DATETIME_PATTERN),
            $actual->format(self::ZONED_DATETIME_PATTERN),
            $message,
        );
    }
}
