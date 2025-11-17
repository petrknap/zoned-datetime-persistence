<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeInterface;

final class UtcWithLocalTest extends UtcTestCase
{
    public function test_constructs_itself(): void
    {
        $utcWithLocal = $this->getInstance($this->zonedDateTime);

        self::assertDateTimeEquals(
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $utcWithLocal->getUtcDateTime(),
            'Incorrect UTC date-time',
        );
        self::assertDateTimeEquals(
            $this->localDateTime,
            $utcWithLocal->getLocalDateTime(),
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

    protected function getInstance(DateTimeInterface $zonedDateTime): UtcWithLocal
    {
        return new UtcWithLocal($zonedDateTime);
    }
}
