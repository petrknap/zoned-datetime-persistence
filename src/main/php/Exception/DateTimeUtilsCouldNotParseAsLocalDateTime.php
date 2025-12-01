<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime\Exception;

use RuntimeException;

final class DateTimeUtilsCouldNotParseAsLocalDateTime extends RuntimeException implements DateTimeUtilsException
{
    public function __construct(
        private readonly string $datetime,
        private readonly string $format,
        string $localDateTimeType,
    ) {
        parent::__construct(sprintf(
            'Could not parse the given datetime of string(%d) as %s using format `%s`',
            strlen($datetime),
            $localDateTimeType,
            $format,
        ));
    }

    public function getDatetime(): string
    {
        return $this->datetime;
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
