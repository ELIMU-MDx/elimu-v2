<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use Domain\Rdml\DataTransferObjects\MeltingCurveDataPoint;
use Illuminate\Support\Collection;

/**
 * @method MeltingCurveDataPoint offsetGet($key)
 * @method MeltingCurveDataPoint first(callable $callback = null, ?MeltingCurveDataPoint $default = null)
 * @method MeltingCurveDataPoint last(callable $callback = null, ?MeltingCurveDataPoint $default = null)
 * @method MeltingCurveDataPoint firstWhere($key, $operator = null, $value = null)
 */
final class MeltingCurveDataCollection extends Collection
{

}
