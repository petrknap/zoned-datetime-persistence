<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateInterval;

final class DateTimeUtilsTest extends TestCase
{
    public function testAsUtcInstantAtOffset(): void
    {
        self::assertEquals(
            $this->utcDateTime->add(new DateInterval('PT' . self::ZONED_DATETIME_OFFSET . 'S'))->getTimestamp(),
            DateTimeUtils::asUtcInstantAtOffset($this->localDateTime, self::ZONED_DATETIME_OFFSET)->getTimestamp(),
        );
    }

    public function testParseAsLocalDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->localDateTime,
            DateTimeUtils::parseAsLocalDateTime(self::LOCAL_DATETIME, self::LOCAL_DATETIME_PATTERN),
        );
    }

    public function testParseAsLocalDateTimeThrowsOnIncorrectText(): void
    {
        self::expectException(Exception\DateTimeUtilsCouldNotParseAsLocalDateTime::class);

        DateTimeUtils::parseAsLocalDateTime('this is not a date', self::LOCAL_DATETIME_PATTERN);
    }

    public function testSecondsBetween(): void
    {
        self::assertEquals(
            self::ZONED_DATETIME_OFFSET,
            DateTimeUtils::secondsBetween(JavaSe8\Time::toLocalDateTime($this->utcDateTime), $this->localDateTime),
        );
    }
}
