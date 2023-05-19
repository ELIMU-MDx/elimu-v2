<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use BadMethodCallException;
use Domain\Rdml\DataTransferObjects\Target;
use Illuminate\Support\Collection;

/**
 * @method Target offsetGet($key)
 * @method Target first(callable $callback = null, ?Target $default = null)
 * @method Target last(callable $callback = null, ?Target $default = null)
 * @method Target firstWhere($key, $operator = null, $value = null)
 */
final class TargetCollection extends Collection
{
    public function getById(string $id): Target
    {
        $target = $this->first(fn (Target $target) => $target->id === $id);

        return $target ?? throw new BadMethodCallException("No target with id '{$id}' exists");
    }
}
