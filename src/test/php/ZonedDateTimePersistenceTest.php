<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;

final class ZonedDateTimePersistenceTest extends TestCase
{
    public function testComputesUtcCompanion(): void
    {
        $utcCompanion = ZonedDateTimePersistence::computeUtcCompanion($this->zonedDateTime);

        self::assertEquals(0, $utcCompanion->getOffset());
        self::assertEquals($this->zonedDateTime->getTimestamp(), $utcCompanion->getTimestamp());
    }

    #[DataProvider('dataComputesZonedDateTime')]
    public function testComputesZonedDateTime(DateTimeImmutable $zonedDateTime): void
    {
        self::assertEquals(self::OFFSET, $zonedDateTime->getOffset());
        self::assertZonedDateTime($this->zonedDateTime, $zonedDateTime);
    }

    public static function dataComputesZonedDateTime(): array
    {
        $zonedDateTime = JavaSe8\Time::zonedDateTime(new DateTimeImmutable(self::ZONED_DATETIME));
        $utcCompanion = ZonedDateTimePersistence::computeUtcCompanion($zonedDateTime);
        $localDateTime = JavaSe8\Time::toLocalDateTime($zonedDateTime);
        return [
            'DateTime + UTC companion' => [ZonedDateTimePersistence::computeZonedDateTime(
                $localDateTime,
                utcCompanion: $utcCompanion,
            )],
            'string + UTC companion + format' => [ZonedDateTimePersistence::computeZonedDateTime(
                $localDateTime->format(self::FORMAT),
                utcCompanion: $utcCompanion->format(self::FORMAT),
                format: self::FORMAT,
            )],
        ];
    }
}
