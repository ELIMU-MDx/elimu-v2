<?php

declare(strict_types=1);

namespace Domain\Rdml\DataTransferObjects;

use Domain\Rdml\Enums\LabelFormat;
use Spatie\DataTransferObject\DataTransferObject;

final class PCRFormat extends DataTransferObject
{
    public int $rows;

    public int $columns;

    public LabelFormat $rowLabel;

    public LabelFormat $columnLabel;

}
