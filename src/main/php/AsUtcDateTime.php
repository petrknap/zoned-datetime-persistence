<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

use Carbon\Traits\Converter;
use DateTimeInterface;
use DateTimeZone;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
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
        $this->illuminateResilience($model, $key);

        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException('$value must be string or null');
        }

        $localDateTime = $this->carbonFromString($model, $value);
        $zonedDateTime = ZonedDateTimePersistence::computeZonedDateTime(
            $localDateTime,
            timezone: $this->utc,
        );

        /** @var Carbon */
        return Carbon::createFromInterface($zonedDateTime);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string|null
    {
        $this->illuminateResilience($model, $key);

        if ($value === null) {
            return null;
        }

        $dateTime = match (is_string($value)) {
            true => $this->carbonFromString($model, $value), // Timezone information probably lost
            false => $value,
        };

        $formattedDateTime = ZonedDateTimePersistence::computeUtcDateTime($dateTime)
            ->format($this->dateTimeFormat ?? $model->getDateFormat());

        if ($this->isReadonly && $formattedDateTime !== $attributes[$key]) { // Eloquent sometimes calls setter with cached value
            throw new LogicException(sprintf('%s::$%s is readonly', get_class($model), $key));
        }

        return $formattedDateTime;
    }

    /**
     * @param string $value treated as UTC when timezone information is not present
     *
     * @note it contains fallback to {@see Carbon::parse()} due to {@see HasAttributes::serializeDate()}->{@see Converter::toJSON()} call
     */
    private function carbonFromString(Model $model, string $value): Carbon
    {
        try {
            /** @var Carbon */
            return Carbon::createFromFormat(
                $this->dateTimeFormat ?? $model->getDateFormat(),
                $value,
                $this->utc,
            );
        } catch (Throwable $error) {
            try {
                /** @var Carbon */
                return Carbon::parse(
                    $value,
                    $this->utc,
                );
            } catch (Throwable) {
                throw $error;
            }
        }
    }

    /**
     * Warns about known "Illuminated" issues
     */
    private function illuminateResilience(Model $model, string $key): void
    {
        if (in_array($key, $model->getDates(), strict: true)) {
            user_error(sprintf(
                '%s::$%s is registered as date, do not cast it twice; use %s::withUtc() or %s::withSystemTimezone() instead',
                get_class($model),
                $key,
                AsUtc::class,
                AsUtc::class,
            ), E_USER_WARNING);
        }
    }
}
