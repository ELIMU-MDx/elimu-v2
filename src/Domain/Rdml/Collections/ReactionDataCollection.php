<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use Domain\Rdml\DataTransferObjects\ReactionData;
use Illuminate\Support\Collection;

/**
 * @method ReactionData offsetGet($key)
 * @method ReactionData first(callable $callback = null, ?ReactionData $default = null)
 * @method ReactionData last(callable $callback = null, ?ReactionData $default = null)
 * @method ReactionData firstWhere($key, $operator = null, $value = null)
 */
final class ReactionDataCollection extends Collection
{

}
