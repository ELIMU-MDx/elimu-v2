<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Domain\Rdml\Collections\ReactionCollection;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class Run extends DataTransferObject implements Arrayable
{
    public string $id;

    public ?string $description;

    public ?Experimenter $experimenter;

    public ?string $instrument;

    public ?DataCollectionSoftware $dataCollectionSoftware;

    public ?PCRFormat $pcrFormat;

    public ReactionCollection $reactions;
}
