<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Domain\Rdml\Collections\ReactionCollection;
use Spatie\DataTransferObject\DataTransferObject;

final class Run extends DataTransferObject
{
    public string $id;

    public ?string $description;

    public ?Experimenter $experimenter;

    public ?string $instrument;

    public ?DataCollectionSoftware $dataCollectionSoftware;

    public ?PCRFormat $pcrFormat;

    public ReactionCollection $reactions;
}
