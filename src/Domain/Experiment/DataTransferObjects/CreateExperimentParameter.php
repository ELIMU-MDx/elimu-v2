<?php

declare(strict_types=1);

namespace Domain\Experiment\DataTransferObjects;

use App\Models\Experiment;
use Illuminate\Http\UploadedFile;
use Support\Data;

final class CreateExperimentParameter extends Data
{
    public function __construct(
        readonly public UploadedFile $rdml,
        readonly public int $assayId,
        readonly public int $studyId,
        readonly public int $creatorId,
        readonly public ?string $eln = null,
    ) {

    }

    public function getExperiment(): Experiment
    {
        return new Experiment([
            'study_id' => $this->studyId,
            'assay_id' => $this->assayId,
            'user_id' => $this->creatorId,
            'name' => $this->rdml->getClientOriginalName(),
            'rdml_path' => $this->rdml->storePublicly('rdmls'),
            'eln' => $this->eln,
        ]);
    }
}
