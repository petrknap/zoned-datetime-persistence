<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateInterval;

final class DateTimeUtilsTest extends TestCase
{
    public function testAsUtcInstantAtOffset(): void
    {
        self::assertEquals(
            $this->utcDateTime->add(new DateInterval('PT' . self::OFFSET . 'S'))->getTimestamp(),
            DateTimeUtils::asUtcInstantAtOffset($this->localDateTime, self::OFFSET)->getTimestamp(),
        );
    }

    public function testParseAsLocalDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->localDateTime,
            DateTimeUtils::parseAsLocalDateTime(self::DATETIME, self::FORMAT),
        );
    }

    public function testParseAsLocalDateTimeThrowsOnIncorrectText(): void
    {
        self::expectException(Exception\DateTimeUtilsCouldNotParseAsLocalDateTime::class);

        DateTimeUtils::parseAsLocalDateTime('this is not a date', self::FORMAT);
    }

    public function testSecondsBetween(): void
    {
        self::assertEquals(
            self::OFFSET,
            DateTimeUtils::secondsBetween(JavaSe8\Time::toLocalDateTime($this->utcDateTime), $this->localDateTime),
        );
    }
}
