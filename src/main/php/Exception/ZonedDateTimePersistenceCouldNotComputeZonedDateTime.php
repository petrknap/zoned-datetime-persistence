<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence\Exception;

use RuntimeException;
use Throwable;

final class ZonedDateTimePersistenceCouldNotComputeZonedDateTime extends RuntimeException implements ZonedDateTimePersistenceException
{
    public function __construct(Throwable $cause)
    {
        parent::__construct($cause->getMessage(), previous: $cause);
    }
}
