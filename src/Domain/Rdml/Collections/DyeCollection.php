<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use BadMethodCallException;
use Domain\Rdml\DataTransferObjects\Dye;
use Illuminate\Support\Collection;

/**
 * @method Dye offsetGet($key)
 * @method Dye first(callable $callback = null, ?Dye $default = null)
 * @method Dye last(callable $callback = null, ?Dye $default = null)
 * @method Dye firstWhere($key, $operator = null, $value = null)
 */
final class DyeCollection extends Collection
{
    public function getById(string $id): Dye
    {
        $dye = $this->first(function (Dye $dye) use ($id) {
            return $dye->id === $id;
        });

        return $dye ?? throw new BadMethodCallException("Could not find dye with id {$id}");
    }
}
