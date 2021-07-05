<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class Run extends DataTransferObject
{
    public string $id;

    public ?string $description;

    public ?string $experimenterId;

    public ?string $instrument;

    public ?DataCollectionSoftware $dataCollectionSoftware;

    public ?PCRFormat $pcrFormat;

    public array $reactions;
}
