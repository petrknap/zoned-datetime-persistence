<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;

/**
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 */
abstract class UtcTestCase extends TestCase
{
    abstract public function test_constructs_itself(): void;

    /**
     * @note use arguments typed AS IS in embeddable
     */
    abstract public function test_fromStored_objects(): void;

    abstract public function test_fromStored_objects_of_null(): void;

    /**
     * @note use arguments typed as scalars
     */
    abstract public function test_fromStored_scalars(): void;

    abstract public function test_fromStored_scalars_of_null(): void;

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
