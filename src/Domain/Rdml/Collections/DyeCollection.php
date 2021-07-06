<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use BadMethodCallException;
use Domain\Rdml\DataTransferObjects\Dye;
use Exception;
use Illuminate\Support\Collection;
use OutOfBoundsException;

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
