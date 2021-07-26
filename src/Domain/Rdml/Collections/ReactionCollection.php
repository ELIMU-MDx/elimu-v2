<?php

declare(strict_types=1);

namespace Domain\Rdml\Collections;

use Domain\Rdml\DataTransferObjects\Reaction;
use Illuminate\Support\Collection;

/**
 * @method Reaction offsetGet($key)
 * @method Reaction first(callable $callback = null, ?Reaction $default = null)
 * @method Reaction last(callable $callback = null, ?Reaction $default = null)
 * @method Reaction firstWhere($key, $operator = null, $value = null)
 */
final class ReactionCollection extends Collection
{
}
