<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence\JavaSe8;

use DateTime;
use PetrKnap\ZonedDateTimePersistence\TestCase;

final class TimeTest extends TestCase
{
    public function test_toInstant(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            Time::toInstant($this->localDateTime, self::ZONED_DATETIME_OFFSET),
        );
    }

    public function test_toLocalDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->localDateTime,
            Time::toLocalDateTime($this->zonedDateTime),
        );
    }

    public function test_localDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->localDateTime,
            Time::localDateTime(new DateTime(self::LOCAL_DATETIME)),
        );
        self::assertNull(Time::localDateTime(null));
    }

    public function test_zoneDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            Time::zonedDateTime(new DateTime(self::ZONED_DATETIME)),
        );
        self::assertNull(Time::zonedDateTime(null));
    }

    public function test_zoneOffset(): void
    {
        self::assertEquals(
            self::ZONED_DATETIME_OFFSET,
            Time::zoneOffset(self::ZONED_DATETIME_OFFSET)->getOffset(new DateTime()),
        );
    }
}
