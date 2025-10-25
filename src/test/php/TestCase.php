<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
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
    protected const ZONED_DATETIME = self::DATETIME . ' GMT+0200';
    protected const ZONED_FORMAT = self::FORMAT . ' T';

    /**
     * @var LocalDateTime
     */
    protected DateTimeImmutable $localDateTime;
    /**
     * @var ZonedDateTime
     */
    protected DateTimeImmutable $zonedDateTime;

    protected function setUp(): void
    {
        parent::setUp();
        $dateTime = new DateTimeImmutable(self::ZONED_DATETIME);
        $this->zonedDateTime = JavaSe8\Time::zonedDateTime($dateTime);
        $this->localDateTime = JavaSe8\Time::toLocalDateTime($this->zonedDateTime);
    }

    protected static function assertZonedDateTime(DateTimeImmutable $expected, DateTimeImmutable $actual): void
    {
        self::assertSame(
            $expected->format(self::ZONED_FORMAT),
            $actual->format(self::ZONED_FORMAT),
        );
    }
}
