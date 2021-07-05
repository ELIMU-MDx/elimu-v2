<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class Reaction extends DataTransferObject
{
    public Sample $sample;

    public ?float $cq;

    public bool $excluded;

    /** @var \Domain\Rdml\DataTransferObjects\ReactionData[] */
    public array $data;
}
