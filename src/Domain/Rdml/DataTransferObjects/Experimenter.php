<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class Experimenter extends DataTransferObject
{
    public string $id;

    public string $firstName;

    public string $lastName;

    public ?string $email;

    public ?string $labName;

    public ?string $labAddress;
}
