<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use Domain\Rdml\DataTransferObjects\Experimenter;
use Illuminate\Support\Collection;

/**
 * @method Experimenter offsetGet($key)
 * @method Experimenter first(callable $callback = null, ?Experimenter $default = null)
 * @method Experimenter last(callable $callback = null, ?Experimenter $default = null)
 * @method Experimenter firstWhere($key, $operator = null, $value = null)
 */
final class ExperimenterCollection extends Collection
{
    public function findById(?string $id): ?Experimenter
    {
        if ($id === null) {
            return null;
        }

        return $this->first(function (Experimenter $experimenter) use ($id) {
            return $experimenter->id === $id;
        });
    }
}
