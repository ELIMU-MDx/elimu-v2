<?php

declare(strict_types=1);

namespace Domain\Study\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class StudyMemberParameters extends DataTransferObject
{
    public string $email;

    public string $password;

    public string $name;
}
