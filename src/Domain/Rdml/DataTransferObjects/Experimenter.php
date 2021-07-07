<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

final class Experimenter extends DataTransferObject implements Arrayable
{
    public string $id;

    public ?string $firstName;

    public ?string $lastName;

    public ?string $email;

    public ?string $labName;

    public ?string $labAddress;
}
