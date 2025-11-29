<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence\Some;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use PetrKnap\ZonedDateTimePersistence\AsPrivate;
use PetrKnap\ZonedDateTimePersistence\AsUtc;

/**
 * @property string $content
 * @property Carbon $created_at
 * @property Carbon|null $deleted_at
 * @property Carbon $created_at_utc
 * @property Carbon|null $deleted_at_utc
 */
final class NoteModel extends Model
{
    public const CREATED_AT = 'created_at_utc';
    public const UPDATED_AT = null;

    protected $table = 'notes';

    /**
     * Example: UTC date-time with local date-time
     */
    protected function createdAt(): Attribute
    {
        return AsUtc::withLocal(
            'created_at__utc',
            'created_at__local',
            $this->getDateFormat(),
        );
    }

    /**
     * Example: nullable attribute
     */
    protected function deletedAt(): Attribute
    {
        return AsUtc::withLocal(
            'deleted_at__utc',
            'deleted_at__local',
            $this->getDateFormat(),
        );
    }

    protected function casts(): array
    {
        return [
            'created_at__utc' => AsPrivate::class,
            'created_at__local' => AsPrivate::class,
            'deleted_at__utc' => AsPrivate::class,
            'deleted_at__local' => AsPrivate::class,
            // Example: UTC date-time cast
            'created_at_utc' => AsUtc::dateTime(),
            // Example: casted nullable
            'deleted_at_utc' => AsUtc::dateTime(),
        ];
    }
}
