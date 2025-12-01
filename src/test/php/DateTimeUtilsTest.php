<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

use DateInterval;

final class DateTimeUtilsTest extends TestCase
{
    public function test_asUtcInstantAtOffset(): void
    {
        self::assertEquals(
            $this->utcDateTime->add(new DateInterval('PT' . self::ZONED_DATETIME_OFFSET . 'S'))->getTimestamp(),
            DateTimeUtils::asUtcInstantAtOffset($this->localDateTime, self::ZONED_DATETIME_OFFSET)->getTimestamp(),
        );
    }

    public function test_parseAsLocalDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->localDateTime,
            DateTimeUtils::parseAsLocalDateTime(self::LOCAL_DATETIME, self::LOCAL_DATETIME_FORMAT),
        );
    }

    public function test_parseAsLocalDateTime_throws_on_incorrect_datetime(): void
    {
        self::expectException(Exception\DateTimeUtilsCouldNotParseAsLocalDateTime::class);

        DateTimeUtils::parseAsLocalDateTime('this is not a local date-time', self::LOCAL_DATETIME_FORMAT);
    }

    public function test_secondsBetween(): void
    {
        self::assertEquals(
            self::ZONED_DATETIME_OFFSET,
            DateTimeUtils::secondsBetween(JavaSe8\Time::toLocalDateTime($this->utcDateTime), $this->localDateTime),
        );
    }
}
