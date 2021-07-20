<?php

declare(strict_types=1);

namespace Domain\Rdml\LabelFormats;

interface LabelFormat
{
    public function getPosition(int $position): string;
}
