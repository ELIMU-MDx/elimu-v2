<?php

declare(strict_types=1);

namespace Domain\Evaluation\Collections;

use Domain\Evaluation\DataTransferObjects\TargetData;
use Illuminate\Support\Collection;

/**
 * @method TargetData offsetGet($key)
 * @method TargetData first(callable $callback = null, ?TargetData $default = null)
 * @method TargetData last(callable $callback = null, ?TargetData $default = null)
 * @method TargetData firstWhere($key, $operator = null, $value = null)
 */
final class TargetDataCollection extends Collection
{
}
