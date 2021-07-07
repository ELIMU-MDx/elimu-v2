<?php

declare(strict_types=1);

namespace Domain\Evaluation\Collections;

use Domain\Evaluation\DataTransferObjects\SampleData;
use Illuminate\Support\Collection;

/**
 * @method SampleData offsetGet($key)
 * @method SampleData first(callable $callback = null, ?SampleData $default = null)
 * @method SampleData last(callable $callback = null, ?SampleData $default = null)
 * @method SampleData firstWhere($key, $operator = null, $value = null)
 */
final class SampleDataCollection extends Collection
{

}
