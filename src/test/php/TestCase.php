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
    protected const DATETIME = '2025-10-25 16:05:55.000000';
    protected const FORMAT = JavaSe8\Time::TIMEZONE_LESS_FORMAT;
    protected const OFFSET = 7200;
    protected const ZONED_DATETIME = self::DATETIME . ' +02:00';
    protected const ZONED_FORMAT = self::FORMAT . ' P';

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

        $this->localDateTime = JavaSe8\Time::localDateTime(new DateTimeImmutable(self::DATETIME));
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
            $expected->format(self::ZONED_FORMAT),
            $actual->format(self::ZONED_FORMAT),
            $message,
        );
    }
}
