<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence\Exception;

use RuntimeException;

final class DateTimeUtilsCouldNotParseAsLocalDateTime extends RuntimeException implements DateTimeUtilsException
{
    public function __construct(
        public readonly string $text,
        public readonly string $pattern,
    ) {
        parent::__construct(sprintf(
            'Could not parse string(%d) as %s',
            strlen($text),
            $pattern,
        ));
    }
}
