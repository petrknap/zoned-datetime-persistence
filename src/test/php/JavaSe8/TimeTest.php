<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence\JavaSe8;

use PetrKnap\ZonedDateTimePersistence\TestCase;

final class TimeTest extends TestCase
{
    public function testToLocalDateTimeWorks(): void
    {
        $localDateTime = Time::toLocalDateTime($this->zonedDateTime);

        self::assertSame([
            'formated' => $this->zonedDateTime->format(self::FORMAT),
            'timestamp' => $this->zonedDateTime->getTimestamp() + self::OFFSET,
        ], [
            'formated' => $localDateTime->format(self::FORMAT),
            'timestamp' => $localDateTime->getTimestamp(),
        ], 'Formated values must be the same, but timestamps must be shifted by an offset.');
    }
}
