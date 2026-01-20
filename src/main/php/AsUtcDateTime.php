<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

use Carbon\Exceptions\InvalidFormatException;
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

        $localDateTime = self::normalizeValue($value, $this->dateTimeFormat ?? $model->getDateFormat(), $this->utc);
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

        $dateTime = self::normalizeValue(
            $value,
            $this->dateTimeFormat ?? $model->getDateFormat(),
            $this->utc, // Timezone information probably lost; treated as UTC when timezone information is not present
        );

        $formattedDateTime = ZonedDateTimePersistence::computeUtcDateTime($dateTime)
            ->format($this->dateTimeFormat ?? $model->getDateFormat());

        if ($this->isReadonly && $formattedDateTime !== $attributes[$key]) { // Eloquent sometimes calls setter with cached value
            throw new LogicException(sprintf('%s::$%s is readonly', get_class($model), $key));
        }

        return $formattedDateTime;
    }

    /**
     * @internal
     *
     * @return ($value is null ? null : DateTimeInterface)
     *
     * @note it contains fallback to {@see Carbon::parse()} due to {@see HasAttributes::serializeDate()}->{@see Converter::toJSON()} call
     */
    public static function normalizeValue(
        DateTimeInterface|string|null $value,
        string $dateTimeFormat,
        DateTimeZone|string $fallbackTimezone,
    ): DateTimeInterface|null {
        if ($value === null || $value instanceof DateTimeInterface) {
            return $value;
        }
        try {
            /** @var Carbon */
            return Carbon::createFromFormat($dateTimeFormat, $value, $fallbackTimezone);
        } catch (InvalidFormatException $invalidFormatException) { // @phpstan-ignore catch.neverThrown
            try {
                /** @var Carbon */
                return Carbon::parse($value, $fallbackTimezone);
            } catch (Throwable) {
                throw $invalidFormatException;
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
                '%s::$%s is registered as date, do not cast it twice; use %s instead',
                get_class($model),
                $key,
                implode('::', AsUtc::CAST_ALTERNATIVE),
            ), E_USER_WARNING);
        }
    }
}
