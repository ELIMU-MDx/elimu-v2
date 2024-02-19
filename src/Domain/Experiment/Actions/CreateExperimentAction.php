<?php

declare(strict_types=1);

namespace Domain\Experiment\Actions;

use App\Models\Experiment;
use App\Models\Measurement;
use App\Models\Sample;
use Domain\Experiment\DataTransferObjects\CreateExperimentParameter;
use Domain\Experiment\Jobs\ImportDataPointJob;
use Domain\Rdml\Converters\RdmlConverter;
use Domain\Rdml\DataTransferObjects\QuantifyConfiguration;
use Domain\Rdml\RdmlReader;
use Illuminate\Database\Connection;
use JsonException;

final class CreateExperimentAction
{
    public function __construct(
        private readonly RdmlReader $rdmlReader,
        private readonly Connection $connection,
        private readonly RecalculateResultsAction $recalculateResultsAction,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function execute(CreateExperimentParameter $parameter): Experiment
    {
        return $this->connection->transaction(function () use ($parameter) {
            $rdml = $this->rdmlReader->read($parameter->rdml);
            $experiment = $parameter->getExperiment();
            $experiment->experiment_date = $rdml->updatedAt;
            $experiment->instrument = $rdml->instrument;
            $experiment->save();
            if ($rdml->quantifyConfigurations->isNotEmpty()) {
                $experiment
                    ->quantifyParameters()
                    ->createMany($rdml
                        ->quantifyConfigurations
                        ->map(fn (QuantifyConfiguration $configuration) => [
                            'target' => $configuration->target,
                            'slope' => $configuration->slope,
                            'intercept' => $configuration->intercept,
                            'correlation_coefficient' => $configuration->correlationCoefficient,
                        ])
                    );
            }

            $measurements = (new RdmlConverter($rdml))->toMeasurements();

            $sampleLookupTable = Sample::whereIn('identifier', $measurements->pluck('sample.identifier'))
                ->where('study_id', $parameter->studyId)
                ->pluck('id', 'identifier');

            $sampleLookupTable = $measurements
                ->filter(fn (Measurement $measurement) => ! $sampleLookupTable->has($measurement->sample->identifier))
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
                ->each(function (Measurement $measurement) use ($experiment, $sampleLookupTable) {
                    $measurement->experiment_id = $experiment->id;
                    $measurement->created_at = $experiment->created_at;
                    $measurement->updated_at = $experiment->updated_at;
                    $measurement->sample_id = $sampleLookupTable->get($measurement->sample->identifier);
                    unset($measurement->sample);

                    $measurement->save();
                });

            $this->recalculateResultsAction->execute(
                Measurement::whereHas('experiment', fn ($join) => $join
                    ->where('study_id', $parameter->studyId)
                    ->where('assay_id', $parameter->assayId))
                    ->whereIn('sample_id', $measurements->pluck('sample_id'))
                    ->whereIn('target', $measurements->pluck('target'))
                    ->get()
            );

            dispatch(new ImportDataPointJob($experiment));

            return $experiment;
        });
    }
}
