<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeInterface;

abstract class UtcTestCase extends TestCase
{
    abstract public function test_constructs_itself(): void;

    public function test_getUtcDateTime_as_formatted_string(): void
    {
        self::assertEquals(
            $this->utcDateTime->format(self::LOCAL_DATETIME_FORMAT),
            $this->getInstance($this->zonedDateTime)->getUtcDateTime(self::LOCAL_DATETIME_FORMAT)
        );
    }

    public function test_toZonedDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            $this->getInstance($this->zonedDateTime)->toZonedDateTime(),
        );
    }

    abstract protected function getInstance(DateTimeInterface $zonedDateTime): Utc;
}
