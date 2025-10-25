<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;

final class DateTimeWithUtcCompanionTest extends TestCase
{
    #[DataProvider('dataConstructsItself')]
    public function testConstructsItself(
        DateTimeWithUtcCompanion $dateTimeWithUtcCompanion,
        DateTimeImmutable $expectedDateTime,
        DateTimeImmutable $expectedUtcCompanion,
    ): void {
        self::assertZonedDateTime($expectedDateTime, $dateTimeWithUtcCompanion->dateTime);
        self::assertZonedDateTime($expectedUtcCompanion, $dateTimeWithUtcCompanion->utcCompanion);
    }

    public static function dataConstructsItself(): array
    {
        $zonedDateTime = DateTimeUtils::parse(self::ZONED_DATETIME, self::ZONED_FORMAT);
        $utcDateTime = ZonedDateTimePersistence::computeUtcCompanion($zonedDateTime);

        return [
            'zoned datetime' => [
                new DateTimeWithUtcCompanion(
                    dateTime: $zonedDateTime,
                ),
                $zonedDateTime,
                $utcDateTime,
            ],
            'local datetime and local UTC companion' => [
                new DateTimeWithUtcCompanion(
                    dateTime: JavaSe8\Time::toLocalDateTime($zonedDateTime),
                    utcCompanion: JavaSe8\Time::toLocalDateTime($utcDateTime),
                ),
                $zonedDateTime,
                $utcDateTime,
            ],
            'zoned datetime and zoned UTC companion' => [
                new DateTimeWithUtcCompanion(
                    dateTime: $zonedDateTime,
                    utcCompanion: $utcDateTime,
                ),
                $zonedDateTime,
                $utcDateTime,
            ],
        ];
    }
}
