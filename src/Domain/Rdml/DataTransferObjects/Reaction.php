<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Domain\Rdml\Collections\ReactionDataCollection;
use Spatie\DataTransferObject\DataTransferObject;

final class Reaction extends DataTransferObject
{
    public Sample $sample;

    public ReactionDataCollection $data;
}
