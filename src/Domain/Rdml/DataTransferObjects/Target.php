<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Support\Data;

final class Target extends Data
{
    public function __construct(
        public string $id,
        public string $type,
        public string $dye,
    ) {
    }
}
