<?php

declare(strict_types=1);

namespace Domain\Evaluation\Collections;

use Domain\Evaluation\DataTransferObjects\QpcrResult;
use Illuminate\Support\Collection;

/**
 * @method QpcrResult offsetGet($key)
 * @method QpcrResult first(callable $callback = null, ?QpcrResult $default = null)
 * @method QpcrResult last(callable $callback = null, ?QpcrResult $default = null)
 * @method QpcrResult firstWhere($key, $operator = null, $value = null)
 */
final class QpcrResultCollection extends Collection
{

}
