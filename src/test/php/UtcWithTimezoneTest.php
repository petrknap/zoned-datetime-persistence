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

    public function test_fromStored_objects(): void
    {
        self::assertInstance(
            UtcWithTimezone::fromStored(
                JavaSe8\Time::toLocalDateTime($this->utcDateTime),
                $this->zonedDateTime->getTimezone(),
            ),
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $this->zonedDateTime->getTimezone(),
        );
    }

    public function test_fromStored_objects_of_null(): void
    {
        self::assertNull(UtcWithTimezone::fromStored(null, null));
    }

    public function test_fromStored_scalars(): void
    {
        self::assertInstance(
            UtcWithTimezone::fromStored(
                $this->utcDateTime->format(self::LOCAL_DATETIME_FORMAT),
                $this->zonedDateTime->getTimezone()->getName(),
                self::LOCAL_DATETIME_FORMAT,
            ),
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $this->zonedDateTime->getTimezone(),
        );
    }

    public function test_fromStored_scalars_of_null(): void
    {
        self::assertNull(UtcWithTimezone::fromStored(null, null, self::LOCAL_DATETIME_FORMAT));
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
