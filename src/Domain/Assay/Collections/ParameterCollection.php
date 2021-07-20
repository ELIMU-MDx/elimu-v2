<?php

declare(strict_types=1);

namespace Domain\Assay\Collections;

use BadMethodCallException;
use Domain\Assay\Models\AssayParameter;
use Illuminate\Database\Eloquent\Collection;

final class ParameterCollection extends Collection
{
    public function getByTarget(string $target): AssayParameter
    {
        return $this->first(function (AssayParameter $parameter) use ($target) {
            return strcasecmp($parameter->target, $target) === 0;
        }, function () use ($target) {
            throw new BadMethodCallException('Could not find assay parameter with target '.$target);
        });
    }
}
