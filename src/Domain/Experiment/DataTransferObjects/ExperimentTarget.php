<?php

namespace Domain\Experiment\DataTransferObjects;

use Support\Data;

final class ExperimentTarget extends Data
{
    public function __construct(
        readonly public string $name,
        /** @var string[] $statusMessage */
        readonly public array $errors = [],
        readonly ?ExperimentTargetQuantification $quantification = null
    ) {
    }
}
