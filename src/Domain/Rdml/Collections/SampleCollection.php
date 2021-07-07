<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use BadMethodCallException;
use Domain\Rdml\DataTransferObjects\Sample;
use Illuminate\Support\Collection;

/**
 * @method Sample offsetGet($key)
 * @method Sample first(callable $callback = null, ?Sample $default = null)
 * @method Sample last(callable $callback = null, ?Sample $default = null)
 * @method Sample firstWhere($key, $operator = null, $value = null)
 */
final class SampleCollection extends Collection
{
    public function getById(string $id): Sample
    {
        $sample = $this->first(function (Sample $sample) use ($id) {
            return $sample->id === $id;
        });

        return $sample ?? throw new BadMethodCallException("Could not find sample with id '{$id}'");
    }
}
