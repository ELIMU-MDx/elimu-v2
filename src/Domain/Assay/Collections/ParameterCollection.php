<?php

declare(strict_types=1);

namespace Domain\Assay\Collections;

use App\Models\AssayParameter;
use BadMethodCallException;
use Illuminate\Database\Eloquent\Collection;

final class ParameterCollection extends Collection
{
    public function getByTarget(string $target): AssayParameter
    {
        return $this->first(fn (AssayParameter $parameter) => strcasecmp($parameter->target, $target) === 0, function () use ($target): never {
            throw new BadMethodCallException('Could not find assay parameter with target '.$target);
        });
    }
}
