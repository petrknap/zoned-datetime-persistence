<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeZone;

/**
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 */
final class UtcWithTimezoneTest extends UtcTestCase
{
    public function test_constructs_itself(): void
    {
        self::assertInstance(
            $this->getInstance($this->zonedDateTime),
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $this->zonedDateTime->getTimezone(),
        );
    }

    public function test_fromValues(): void
    {
        self::assertInstance(
            UtcWithTimezone::fromValues(
                JavaSe8\Time::toLocalDateTime($this->utcDateTime),
                $this->zonedDateTime->getTimezone(),
            ),
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $this->zonedDateTime->getTimezone(),
        );
    }

    public function test_fromValues_of_null(): void
    {
        self::assertNull(UtcWithTimezone::fromValues(null, null));
    }

    public function test_fromFormattedValues(): void
    {
        self::assertInstance(
            UtcWithTimezone::fromFormattedValues(
                $this->utcDateTime->format(self::LOCAL_DATETIME_FORMAT),
                self::LOCAL_DATETIME_FORMAT,
                $this->zonedDateTime->getTimezone()->getName(),
            ),
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $this->zonedDateTime->getTimezone(),
        );
    }

    public function test_fromFormattedValues_of_null(): void
    {
        self::assertNull(UtcWithTimezone::fromFormattedValues(null, self::LOCAL_DATETIME_FORMAT, null));
    }

    public function test_getTimezone_as_formatted_string(): void
    {
        self::assertEquals(
            $this->zonedDateTime->getTimezone()->getName(),
            $this->getInstance($this->zonedDateTime)->getTimezone(true),
        );
    }

    protected function getInstance(DateTimeImmutable $zonedDateTime): UtcWithTimezone
    {
        return new UtcWithTimezone($zonedDateTime);
    }

    /**
     * @param LocalDateTime $expectedUtcDateTime
     */
    private static function assertInstance(
        UtcWithTimezone|null $actual,
        DateTimeImmutable $expectedUtcDateTime,
        DateTimeZone $expectedTimezone,
    ) {
        self::assertNotNull($actual);
        self::assertEquals(
            $expectedUtcDateTime,
            $actual->getUtcDateTime(),
            'Unexpected UTC date-time'
        );
        self::assertEquals(
            $expectedTimezone,
            $actual->getTimezone(),
            'Unexpected timezone'
        );
    }
}
