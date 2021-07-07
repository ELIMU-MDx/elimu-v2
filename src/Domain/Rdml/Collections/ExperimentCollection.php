<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use Domain\Rdml\DataTransferObjects\Experiment;
use Illuminate\Support\Collection;

/**
 * @method Experiment offsetGet($key)
 * @method Experiment first(callable $callback = null, ?Experiment $default = null)
 * @method Experiment last(callable $callback = null, ?Experiment $default = null)
 * @method Experiment firstWhere($key, $operator = null, $value = null)
 */
final class ExperimentCollection extends Collection
{
}
