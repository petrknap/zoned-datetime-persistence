<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;

final class ZonedDateTimePersistenceTest extends TestCase
{
    public function test_computeUtcCompanion(): void
    {
        self::assertDateTimeEquals(
            JavaSe8\Time::localDateTime($this->utcDateTime),
            ZonedDateTimePersistence::computeUtcCompanion($this->zonedDateTime),
        );
    }

    public function test_computeZonedDateTime_from_local_DateTimes(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            ZonedDateTimePersistence::computeZonedDateTime(
                $this->localDateTime,
                JavaSe8\Time::localDateTime($this->utcDateTime),
            ),
        );
    }

    public function test_computeZonedDateTime_from_strings(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            ZonedDateTimePersistence::computeZonedDateTime(
                self::LOCAL_DATETIME,
                JavaSe8\Time::localDateTime($this->utcDateTime)->format(self::LOCAL_DATETIME_FORMAT),
                self::LOCAL_DATETIME_FORMAT,
            ),
        );
    }
}
