<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence\Some;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use PetrKnap\ZonedDateTimePersistence\AsUtc;

/**
 * @property string $content
 * @property Carbon $created_at_utc
 * @property Carbon|null $deleted_at_utc
 */
final class NoteModel extends Model
{
    public const CREATED_AT = 'created_at_utc';
    public const UPDATED_AT = null;

    protected $table = 'notes';

    protected function casts(): array
    {
        return [
            // Example: utc date-time
            'created_at_utc' => AsUtc::dateTime(),
            // Example: nullable cast
            'deleted_at_utc' => AsUtc::dateTime(),
        ];
    }
}
