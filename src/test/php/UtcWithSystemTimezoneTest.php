<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

use DateTimeImmutable;

/**
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 */
final class UtcWithSystemTimezoneTest extends UtcTestCase
{
    public function test_constructs_itself(): void
    {
        self::assertInstance(
            $this->getInstance($this->zonedDateTime),
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
        );
    }

    public function test_fromValues(): void
    {
        self::assertInstance(
            UtcWithSystemTimezone::fromValue(
                $this->utcDateTime,
            ),
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
        );
    }

    public function test_fromValues_of_null(): void
    {
        self::assertNull(UtcWithSystemTimezone::fromValue(null));
    }

    public function test_fromFormattedValues(): void
    {
        self::assertInstance(
            UtcWithSystemTimezone::fromFormattedValue(
                $this->utcDateTime->format(self::LOCAL_DATETIME_FORMAT),
                self::LOCAL_DATETIME_FORMAT,
            ),
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
        );
    }

    public function test_fromFormattedValues_of_null(): void
    {
        self::assertNull(UtcWithSystemTimezone::fromFormattedValue(null, self::LOCAL_DATETIME_FORMAT));
    }

    protected function getInstance(DateTimeImmutable $zonedDateTime): UtcWithSystemTimezone
    {
        return new UtcWithSystemTimezone($zonedDateTime);
    }

    /**
     * @param LocalDateTime $expectedUtcDateTime
     */
    private static function assertInstance(
        UtcWithSystemTimezone|null $actual,
        DateTimeImmutable $expectedUtcDateTime,
    ) {
        self::assertNotNull($actual);
        self::assertEquals(
            $expectedUtcDateTime,
            $actual->getUtcDateTime(),
            'Unexpected UTC date-time'
        );
    }
}
