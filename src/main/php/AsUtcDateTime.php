<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

use DateTimeInterface;
use DateTimeZone;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use LogicException;
use Throwable;

/**
 * Converts zoned date-time into UTC date-time
 *
 * @see AsUtc::dateTime()
 *
 * @implements CastsAttributes<Carbon, DateTimeInterface|string>
 */
final class AsUtcDateTime implements CastsAttributes
{
    private readonly string|null $dateTimeFormat;
    private readonly bool $isReadonly;
    private readonly DateTimeZone $utc;

    public function __construct(
        string|null $dateTimeFormat = null,
        string|bool|null $readonly = null,
    ) {
        if ($dateTimeFormat === '') {
            $dateTimeFormat = null;
        }
        $this->dateTimeFormat = $dateTimeFormat;

        $this->isReadonly = match ($readonly) {
            true, 'true', '1', 'readonly', 'ro' => true,
            default => false,
        };

        $this->utc = new DateTimeZone('UTC');
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): Carbon|null
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException('$value must be string or null');
        }

        /** @var Carbon $localDateTime */
        $localDateTime = Carbon::createFromFormat($this->dateTimeFormat ?? $model->getDateFormat(), $value);
        $zonedDateTime = ZonedDateTimePersistence::computeZonedDateTime(
            $localDateTime,
            timezone: $this->utc,
        );

        /** @var Carbon */
        return Carbon::createFromInterface($zonedDateTime);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string|null
    {
        if ($this->isReadonly) {
            throw new LogicException(sprintf('%s::$%s is readonly', get_class($model), $key));
        }

        if ($value === null) {
            return null;
        }

        if (is_string($value)) { // Timezone information probably lost; treating value as UTC
            try {
                $value = Carbon::createFromFormat(
                    $this->dateTimeFormat ?? $model->getDateFormat(),
                    $value,
                    timezone: $this->utc,
                );
            } catch (Throwable) {
                $value = Carbon::parse(
                    $value,
                    timezone: $this->utc,
                );
            }
        }

        /** @var DateTimeInterface $value */
        return ZonedDateTimePersistence::computeUtcDateTime($value)
            ->format($this->dateTimeFormat ?? $model->getDateFormat());
    }
}
