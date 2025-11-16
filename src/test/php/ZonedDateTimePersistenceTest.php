<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;

final class ZonedDateTimePersistenceTest extends TestCase
{
    public function test_computeUtcDateTime(): void
    {
        self::assertDateTimeEquals(
            JavaSe8\Time::localDateTime($this->utcDateTime),
            ZonedDateTimePersistence::computeUtcDateTime($this->zonedDateTime),
        );
    }

    public function test_computeZonedDateTime_from_utc_and_local_date_time_instances(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            ZonedDateTimePersistence::computeZonedDateTime(
                JavaSe8\Time::localDateTime($this->utcDateTime),
                $this->localDateTime,
            ),
        );
    }

    public function test_computeZonedDateTime_from_utc_and_local_date_time_strings(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            ZonedDateTimePersistence::computeZonedDateTime(
                JavaSe8\Time::localDateTime($this->utcDateTime)->format(self::LOCAL_DATETIME_FORMAT),
                self::LOCAL_DATETIME,
                self::LOCAL_DATETIME_FORMAT,
            ),
        );
    }
}
