<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

use DateTimeImmutable;

/**
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 */
abstract class UtcTestCase extends TestCase
{
    abstract public function test_constructs_itself(): void;

    abstract public function test_fromValues(): void;

    abstract public function test_fromValues_of_null(): void;

    abstract public function test_fromFormattedValues(): void;

    abstract public function test_fromFormattedValues_of_null(): void;

    public function test_asNullable_returns_this(): void
    {
        $instance = $this->getInstance($this->zonedDateTime);

        self::assertSame($instance, $instance->asNullable());
    }

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

    /**
     * @param ZonedDateTime $zonedDateTime
     */
    abstract protected function getInstance(DateTimeImmutable $zonedDateTime): Utc;
}
