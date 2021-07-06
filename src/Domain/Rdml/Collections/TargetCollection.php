<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use BadMethodCallException;
use Domain\Rdml\DataTransferObjects\Target;
use Exception;
use Illuminate\Support\Collection;

final class TargetCollection extends Collection
{

    public function getById(string $id): Target
    {
        $target = $this->first(function (Target $target) use ($id) {
            return $target->id === $id;
        });

        return $target ?? throw new BadMethodCallException("No target with id '{$id}' exists");
    }
}
