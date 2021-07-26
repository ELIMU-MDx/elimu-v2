<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use Domain\Rdml\DataTransferObjects\Run;
use Illuminate\Support\Collection;

/**
 * @method Run offsetGet($key)
 * @method Run first(callable $callback = null, ?Run $default = null)
 * @method Run last(callable $callback = null, ?Run $default = null)
 * @method Run firstWhere($key, $operator = null, $value = null)
 */
final class RunCollection extends Collection
{
}
