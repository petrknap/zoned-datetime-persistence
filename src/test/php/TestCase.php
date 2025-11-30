<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use PHPUnit\Framework\TestCase as Base;

/**
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 */
abstract class TestCase extends Base
{
    protected const LOCAL_DATETIME = '2025-10-25 16:05';
    protected const LOCAL_DATETIME_FORMAT = 'Y-m-d H:i';
    protected const ZONED_DATETIME = '2025-10-25 16:05 +02:00';
    protected const ZONED_DATETIME_FORMAT = 'Y-m-d H:i P';
    protected const ZONED_DATETIME_OFFSET = 7200;

    /**
     * @var LocalDateTime
     */
    protected DateTimeImmutable $localDateTime;
    /**
     * @var ZonedDateTime
     */
    protected DateTimeImmutable $zonedDateTime;
    /**
     * @var ZonedDateTime
     */
    protected DateTimeImmutable $utcDateTime;

    private static string $originalSystemTimezone;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$originalSystemTimezone = date_default_timezone_get();
        date_default_timezone_set('Etc/GMT-2');
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        date_default_timezone_set(self::$originalSystemTimezone);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->localDateTime = new DateTimeImmutable(
            self::LOCAL_DATETIME,
            JavaSe8\Time::zoneOffset(JavaSe8\Time::LOCAL_DATETIME_OFFSET),
        );
        $this->zonedDateTime = new DateTimeImmutable(self::ZONED_DATETIME);
        $this->utcDateTime = $this->zonedDateTime->setTimezone(new DateTimeZone('UTC'));
    }

    protected static function assertDateTimeEquals(
        DateTimeInterface $expected,
        DateTimeInterface|null $actual,
        string $message = '',
    ): void {
        self::assertNotNull($actual, $message);
        self::assertSame(
            $expected::class . ' ' . $expected->format(self::ZONED_DATETIME_FORMAT),
            $actual::class . ' ' . $actual->format(self::ZONED_DATETIME_FORMAT),
            $message,
        );
    }
}
