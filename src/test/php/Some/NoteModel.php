<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime\Some;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use PetrKnap\Persistence\ZonedDateTime\AsPrivate;
use PetrKnap\Persistence\ZonedDateTime\AsUtc;

/**
 * @property string $content
 * @property Carbon $created_at
 * @property-read Carbon $created_at__utc
 * @property Carbon $created_at_2
 * @property-read Carbon $created_at_2__utc
 * @property Carbon $created_at_3
 * @property-read Carbon $created_at_3__utc
 * @property Carbon|null $deleted_at
 * @property-read Carbon|null $deleted_at__utc
 * @property Carbon $created_at_utc
 * @property Carbon|null $deleted_at_utc
 */
final class NoteModel extends Model
{
    public const CREATED_AT = 'created_at_utc';
    public const UPDATED_AT = null;

    protected $table = 'notes';

    /**
     * Example: Eloquent timestamp
     */
    protected function createdAtUtc(): Attribute
    {
        return AsUtc::withUtc(
            'created_at_utc',
            $this->getDateFormat(),
        );
    }

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
     * Example: UTC date-time with timezone
     */
    protected function createdAt2(): Attribute
    {
        return AsUtc::withTimezone(
            'created_at_2__utc',
            $this->getDateFormat(),
            'created_at_2__timezone',
        );
    }

    /**
     * Example: UTC date-time with system timezone
     */
    protected function createdAt3(): Attribute
    {
        return AsUtc::withSystemTimezone(
            'created_at_3__utc',
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
            'created_at__utc' => AsUtc::dateTime(readonly: true),
            'created_at__local' => AsPrivate::class,
            'created_at_2__utc' => AsUtc::dateTime(readonly: true),
            'created_at_2__timezone' => AsPrivate::class,
            'created_at_3__utc' => AsUtc::dateTime(readonly: true),
            'deleted_at__utc' => AsUtc::dateTime(readonly: true),
            'deleted_at__local' => AsPrivate::class,
            // Example: UTC date-time cast
            'deleted_at_utc' => AsUtc::dateTime(),
        ];
    }
}
