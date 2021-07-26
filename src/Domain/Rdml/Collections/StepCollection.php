<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use Domain\Rdml\DataTransferObjects\Step;
use Illuminate\Support\Collection;

/**
 * @method Step offsetGet($key)
 * @method Step first(callable $callback = null, ?Step $default = null)
 * @method Step last(callable $callback = null, ?Step $default = null)
 * @method Step firstWhere($key, $operator = null, $value = null)
 */
final class StepCollection extends Collection
{
}
