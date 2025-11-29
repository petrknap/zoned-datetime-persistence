<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use LogicException;

/**
 * @todo move it to separated package
 *
 * @implements CastsAttributes<null, mixed>
 */
final class AsPrivate implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): null
    {
        return null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): never
    {
        throw new LogicException(sprintf('%s::$%s is private', get_class($model), $key));
    }
}
