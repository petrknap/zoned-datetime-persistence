<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;

/**
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 */
final class UtcWithLocalTest extends UtcTestCase
{
    public function test_constructs_itself(): void
    {
        self::assertInstance(
            $this->getInstance($this->zonedDateTime),
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $this->localDateTime,
        );
    }

    public function test_fromStored_objects(): void
    {
        self::assertInstance(
            UtcWithLocal::fromStored(
                JavaSe8\Time::toLocalDateTime($this->utcDateTime),
                $this->localDateTime,
            ),
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $this->localDateTime,
        );
    }

    public function test_fromStored_objects_of_null(): void
    {
        self::assertNull(UtcWithLocal::fromStored(null, null));
    }

    public function test_fromStored_scalars(): void
    {
        self::assertInstance(
            UtcWithLocal::fromStored(
                $this->utcDateTime->format(self::LOCAL_DATETIME_FORMAT),
                self::LOCAL_DATETIME,
                self::LOCAL_DATETIME_FORMAT,
            ),
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $this->localDateTime,
        );
    }

    public function test_fromStored_scalars_of_null(): void
    {
        self::assertNull(UtcWithLocal::fromStored(null, null, self::LOCAL_DATETIME_FORMAT));
    }

    public function test_getLocalDateTime_as_formatted_string(): void
    {
        self::assertEquals(
            $this->localDateTime->format(self::LOCAL_DATETIME_FORMAT),
            $this->getInstance($this->zonedDateTime)->getLocalDateTime(self::LOCAL_DATETIME_FORMAT),
        );
    }

    protected function getInstance(DateTimeImmutable $zonedDateTime): UtcWithLocal
    {
        return new UtcWithLocal($zonedDateTime);
    }

    /**
     * @param LocalDateTime $expectedUtcDateTime
     * @param LocalDateTime $expectedLocalDateTime
     */
    private static function assertInstance(
        UtcWithLocal|null $actual,
        DateTimeImmutable $expectedUtcDateTime,
        DateTimeImmutable $expectedLocalDateTime,
    ): void {
        self::assertNotNull($actual);
        self::assertEquals(
            $expectedUtcDateTime,
            $actual->getUtcDateTime(),
            'Unexpected UTC date-time'
        );
        self::assertEquals(
            $expectedLocalDateTime,
            $actual->getLocalDateTime(),
            'Unexpected local date-time'
        );
    }
}
