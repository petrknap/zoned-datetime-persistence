<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\DataProvider;

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

    #[DataProvider('dataParseWorks')]
    public function testParseWorks(
        DateTimeInterface|string $dateTime,
        string|null $format,
        DateTimeImmutable $expected,
    ): void {
        self::assertEquals(
            $expected,
            DateTimeUtils::parse($dateTime, $format),
        );
    }

    public static function dataParseWorks(): array
    {
        $dateTime = new DateTime(self::DATETIME);
        $dateTimeImmutable = DateTimeImmutable::createFromInterface($dateTime);

        return [
            'DateTime' => [$dateTime, null, $dateTimeImmutable],
            'string + format' => [self::DATETIME, self::FORMAT, $dateTimeImmutable],
        ];
    }

    public function testParseThrowsOnWrongFormat(): void
    {
        self::expectException(Exception\DateTimeUtilsCouldNotParse::class);

        DateTimeUtils::parse('this is not a date', self::FORMAT);
    }
}
