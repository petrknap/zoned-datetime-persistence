<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence\JavaSe8;

use DateTime;
use PetrKnap\ZonedDateTimePersistence\TestCase;

final class TimeTest extends TestCase
{
    public function testToInstant(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            Time::toInstant($this->localDateTime, self::ZONED_DATETIME_OFFSET),
        );
    }

    public function testToLocalDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->localDateTime,
            Time::toLocalDateTime($this->zonedDateTime),
        );
    }

    public function testLocalDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->localDateTime,
            Time::localDateTime(new DateTime(self::LOCAL_DATETIME)),
        );
    }

    public function testZoneDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            Time::zonedDateTime(new DateTime(self::ZONED_DATETIME)),
        );
    }

    public function testZoneOffset(): void
    {
        self::assertEquals(
            self::ZONED_DATETIME_OFFSET,
            Time::zoneOffset(self::ZONED_DATETIME_OFFSET)->getOffset(new DateTime()),
        );
    }
}
