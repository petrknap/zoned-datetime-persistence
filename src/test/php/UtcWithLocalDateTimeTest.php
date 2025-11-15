<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeInterface;

final class UtcWithLocalDateTimeTest extends UtcDateTimeBaseTestCase
{
    public function test_constructs_itself(): void
    {
        $utcWithLocalDateTime = $this->getInstance($this->zonedDateTime);

        self::assertDateTimeEquals(
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $utcWithLocalDateTime->getUtcDateTime(),
            'Incorrect UTC date-time',
        );
        self::assertDateTimeEquals(
            $this->localDateTime,
            $utcWithLocalDateTime->getLocalDateTime(),
            'Incorrect local date-time',
        );
    }

    public function test_getLocalDateTime_as_formatted_string(): void
    {
        self::assertEquals(
            $this->localDateTime->format(self::LOCAL_DATETIME_FORMAT),
            $this->getInstance($this->zonedDateTime)->getLocalDateTime(self::LOCAL_DATETIME_FORMAT),
        );
    }

    public function test_toZonedDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            $this->getInstance($this->zonedDateTime)->toZonedDateTime(),
        );
    }

    protected function getInstance(DateTimeInterface $zonedDateTime): UtcWithLocalDateTime
    {
        return new UtcWithLocalDateTime($zonedDateTime);
    }
}
