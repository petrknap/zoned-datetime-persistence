<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence\Exception;

use RuntimeException;

final class DateTimeUtilsCouldNotParse extends RuntimeException implements DateTimeUtilsException
{
    public function __construct(
        public readonly string $dateTime,
        public readonly string $format,
    ) {
        parent::__construct(sprintf(
            'Could not parse string(%d) as %s',
            strlen($dateTime),
            $format,
        ));
    }
}
