<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Domain\Rdml\Collections\ReactionDataCollection;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class Reaction extends DataTransferObject implements Arrayable
{
    public Sample $sample;

    public ReactionDataCollection $data;
}
