<?php

declare(strict_types=1);

namespace Domain\Experiment\Actions;

use Domain\Assay\Models\Assay;
use Domain\Experiment\Models\Experiment;
use Domain\Experiment\Models\Measurement;
use Domain\Experiment\Models\Sample;
use Domain\Rdml\Converters\RdmlConverter;
use Domain\Rdml\RdmlReader;
use Illuminate\Database\Connection;
use Illuminate\Http\UploadedFile;

final class CreateExperimentAction
{
    public function __construct(
        private RdmlReader $rdmlReader,
        private Connection $connection,
        private RecalculateResultsAction $recalculateResultsAction
    ) {
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \JsonException
     */
    public function execute(UploadedFile $rdmlFile, Assay $assay, int $studyId, int $creatorId): Experiment
    {
        return $this->connection->transaction(function () use ($rdmlFile, $creatorId, $assay, $studyId) {
            $experiment = Experiment::create([
                'study_id' => $studyId,
                'assay_id' => $assay->id,
                'user_id' => $creatorId,
                'name' => $rdmlFile->getClientOriginalName(),
            ]);
            $measurements = (new RdmlConverter($this->rdmlReader->read($rdmlFile)))->toMeasurements();

            $sampleLookupTable = Sample::whereIn('identifier', $measurements->pluck('sample.identifier'))
                ->where('study_id', $studyId)
                ->pluck('id', 'identifier');

            $sampleLookupTable = $measurements
                ->filter(function (Measurement $measurement) use ($sampleLookupTable) {
                    return !$sampleLookupTable->has($measurement->sample->identifier);
                })
                ->mapWithKeys(function (Measurement $measurement) use ($experiment, $studyId) {
                    $measurement->sample->study_id = $studyId;
                    $measurement->created_at = $experiment->created_at;
                    $measurement->updated_at = $experiment->updated_at;

                    return [
                        $measurement->sample->identifier => $measurement->sample,

                    ];
                })
                ->each(function (Sample $sample) {
                    $sample->save();
                })
                ->values()
                ->pluck('id', 'identifier')
                ->merge($sampleLookupTable);

            $measurements = $measurements
                ->map(function (Measurement $measurement) use ($experiment, $sampleLookupTable) {
                    $measurement->experiment_id = $experiment->id;
                    $measurement->created_at = $experiment->created_at;
                    $measurement->updated_at = $experiment->updated_at;
                    $measurement->sample_id = $sampleLookupTable->get($measurement->sample->identifier);
                    unset($measurement->sample);

                    return $measurement;
                })
                ->each
                ->save();

            $this->recalculateResultsAction->execute(
                Measurement::whereHas('experiment', function ($join) use ($assay, $studyId) {
                    $join
                        ->where('study_id', $studyId)
                        ->where('assay_id', $assay->id);
                })
                    ->whereIn('sample_id', $measurements->pluck('sample_id'))
                    ->whereIn('target', $measurements->pluck('target'))
                    ->get()
            );

            return $experiment;
        });
    }
}
