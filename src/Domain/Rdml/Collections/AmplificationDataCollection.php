<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use Domain\Rdml\DataTransferObjects\AmplificationDataPoint;
use Illuminate\Support\Collection;

/**
 * @method AmplificationDataPoint offsetGet($key)
 * @method AmplificationDataPoint first(callable $callback = null, ?AmplificationDataPoint $default = null)
 * @method AmplificationDataPoint last(callable $callback = null, ?AmplificationDataPoint $default = null)
 * @method AmplificationDataPoint firstWhere($key, $operator = null, $value = null)
 */
final class AmplificationDataCollection extends Collection
{

}
