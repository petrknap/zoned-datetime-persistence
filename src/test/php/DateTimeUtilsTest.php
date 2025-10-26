<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateInterval;

final class DateTimeUtilsTest extends TestCase
{
    public function testAtOffsetWorks(): void
    {
        $offset = new DateInterval('PT' . self::OFFSET . 'S');

        $zonedDateTime = DateTimeUtils::atOffset($this->localDateTime, self::OFFSET);

        self::assertSame([
            'formated' => $this->localDateTime->add($offset)->format(self::FORMAT),
            'timestamp' => $this->localDateTime->getTimestamp(),
        ], [
            'formated' => $zonedDateTime->format(self::FORMAT),
            'timestamp' => $zonedDateTime->getTimestamp(),
        ], 'Formated values must be shifted by an offset, but timestamps must be same.');
    }
}
