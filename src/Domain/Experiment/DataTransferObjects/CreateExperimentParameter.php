<?php

declare(strict_types=1);

namespace Domain\Experiment\DataTransferObjects;

use App\Models\Experiment;
use Illuminate\Http\UploadedFile;
use Spatie\DataTransferObject\DataTransferObject;

final class CreateExperimentParameter extends DataTransferObject
{
    public UploadedFile $rdml;

    public int $assayId;

    public int $studyId;

    public int $creatorId;

    public ?string $eln;

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
