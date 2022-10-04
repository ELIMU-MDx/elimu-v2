<?php

declare(strict_types=1);

namespace Domain\Experiment\Actions;

use Domain\Experiment\DataTransferObjects\CreateExperimentParameter;
use Domain\Experiment\Models\Experiment;
use Domain\Experiment\Models\Measurement;
use Domain\Experiment\Models\Sample;
use Domain\Rdml\Converters\RdmlConverter;
use Domain\Rdml\DataTransferObjects\QuantifyConfiguration;
use Domain\Rdml\Services\RdmlReader;
use Illuminate\Database\Connection;

final class CreateExperimentAction
{
    public function __construct(
        private RdmlReader $rdmlReader,
        private Connection $connection,
        private RecalculateResultsAction $recalculateResultsAction,
    ) {
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \JsonException
     */
    public function execute(CreateExperimentParameter $parameter): Experiment
    {
        return $this->connection->transaction(function () use ($parameter) {
            $rdml = $this->rdmlReader->read($parameter->rdml);
            $experiment = $parameter->getExperiment();
            $experiment->experiment_date = $rdml->updatedAt;
            $experiment->save();
            if ($rdml->quantifyConfigurations->isNotEmpty()) {
                $experiment
                    ->quantifyParameters()
                    ->createMany($rdml
                        ->quantifyConfigurations
                        ->map(fn(QuantifyConfiguration $configuration) => $configuration->toArray())
                    );
            }

            $measurements = (new RdmlConverter($rdml))->toMeasurements();

            $sampleLookupTable = Sample::whereIn('identifier', $measurements->pluck('sample.identifier'))
                ->where('study_id', $parameter->studyId)
                ->pluck('id', 'identifier');

            $sampleLookupTable = $measurements
                ->filter(function (Measurement $measurement) use ($sampleLookupTable) {
                    return !$sampleLookupTable->has($measurement->sample->identifier);
                })
                ->mapWithKeys(function (Measurement $measurement) use ($parameter, $experiment) {
                    $measurement->sample->study_id = $parameter->studyId;
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
                Measurement::whereHas('experiment', function ($join) use ($parameter) {
                    return $join
                        ->where('study_id', $parameter->studyId)
                        ->where('assay_id', $parameter->assayId);
                })
                    ->whereIn('sample_id', $measurements->pluck('sample_id'))
                    ->whereIn('target', $measurements->pluck('target'))
                    ->get()
            );

            return $experiment;
        });
    }
}
