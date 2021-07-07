<?php

declare(strict_types=1);

namespace Domain\Rdml;

use Domain\Rdml\Collections\AmplificationDataCollection;
use Domain\Rdml\Collections\DyeCollection;
use Domain\Rdml\Collections\ExperimentCollection;
use Domain\Rdml\Collections\ExperimenterCollection;
use Domain\Rdml\Collections\MeltingCurveDataCollection;
use Domain\Rdml\Collections\ReactionCollection;
use Domain\Rdml\Collections\ReactionDataCollection;
use Domain\Rdml\Collections\RunCollection;
use Domain\Rdml\Collections\SampleCollection;
use Domain\Rdml\Collections\StepCollection;
use Domain\Rdml\Collections\TargetCollection;
use Domain\Rdml\Collections\ThermalCyclingConditionsCollection;
use Domain\Rdml\DataTransferObjects\AmplificationDataPoint;
use Domain\Rdml\DataTransferObjects\DataCollectionSoftware;
use Domain\Rdml\DataTransferObjects\Dye;
use Domain\Rdml\DataTransferObjects\Experiment;
use Domain\Rdml\DataTransferObjects\Experimenter;
use Domain\Rdml\DataTransferObjects\MeltingCurveDataPoint;
use Domain\Rdml\DataTransferObjects\PCRFormat;
use Domain\Rdml\DataTransferObjects\Rdml;
use Domain\Rdml\DataTransferObjects\Reaction;
use Domain\Rdml\DataTransferObjects\ReactionData;
use Domain\Rdml\DataTransferObjects\Run;
use Domain\Rdml\DataTransferObjects\Sample;
use Domain\Rdml\DataTransferObjects\Step;
use Domain\Rdml\DataTransferObjects\Target;
use Domain\Rdml\DataTransferObjects\Temperature;
use Domain\Rdml\DataTransferObjects\ThermalCyclingConditions;
use Domain\Rdml\Enums\LabelFormat;
use Support\ArrayReader;

final class RdmlParser
{
    /**
     * @throws \JsonException
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function extract(string $xml): Rdml
    {
        $data = $this->xmlToArray($xml);

        $dyes = $this->dyes($data);
        $samples = $this->samples($data);
        $targets = $this->targets($data, $dyes);
        $experimenters = $this->experimenters($data);

        return new Rdml(
            version: $data->getString('@attributes.version', '1.1'),
            createdAt: $data->getDateTime('dateMade'),
            updatedAt: $data->getDateTime('dateUpdated'),
            experimenter: $experimenters,
            dyes: $dyes,
            samples: $samples,
            targets: $targets,
            thermalCyclingConditions: $this->thermalCyclingConditions($data),
            experiments: $this->experiments($data, $samples, $experimenters, $targets),
        );
    }

    private function xmlToArray(string $xml): ArrayReader
    {
        $data = json_decode(json_encode(
            simplexml_load_string($xml),
            JSON_THROW_ON_ERROR
        ), true, 512, JSON_THROW_ON_ERROR);

        return new ArrayReader($data);
    }

    private function dyes(ArrayReader $arrayReader): DyeCollection
    {
        return DyeCollection::make($arrayReader->findList('dye'))
            ->map(function (array $dye) {
                $reader = new ArrayReader($dye);

                return new Dye(
                    id: $reader->getString('@attributes.id'),
                    description: $reader->findString('description'),
                );
            });
    }

    private function samples(ArrayReader $data): SampleCollection
    {
        return SampleCollection::make($data->findList('sample'))
            ->map(function (array $sample) {
                $reader = new ArrayReader($sample);

                return new Sample(
                    id: $reader->getString('@attributes.id'),
                    type: $reader->getString('type'),
                    description: $reader->findString('description')
                );
            });
    }

    private function targets(ArrayReader $data, DyeCollection $dyeCollection): TargetCollection
    {
        return TargetCollection::make($data->findList('target'))
            ->map(function (array $target) use ($dyeCollection) {
                $reader = new ArrayReader($target);

                return new Target(
                    id: $reader->getString('@attributes.id'),
                    type: $reader->getString('type'),
                    dye: $dyeCollection->getById($reader->getString('dyeId.@attributes.id')),
                );
            });
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    private function experimenters(ArrayReader $arrayReader): ExperimenterCollection
    {
        return ExperimenterCollection::make($arrayReader->findList('experimenter'))
            ->map(function (array $experimenter) {
                $reader = new ArrayReader($experimenter);

                return new Experimenter(
                    id: $reader->getString('@attributes.id'),
                    firstName: $reader->findString('firstName'),
                    lastName: $reader->findString('lastName'),
                    email: $reader->findString('email'),
                    labName: $reader->findString('labName'),
                    labAddress: $reader->findString('labAddress'),
                );
            });
    }

    private function thermalCyclingConditions(ArrayReader $data): ThermalCyclingConditionsCollection
    {
        return ThermalCyclingConditionsCollection::make($data->findList('thermalCyclingConditions'))
            ->map(function (array $thermalCyclingConditions) {
                $reader = new ArrayReader($thermalCyclingConditions);

                return new ThermalCyclingConditions(
                    id: $reader->getString('@attributes.id'),
                    description: $reader->findString('description'),
                    lidTemperature: $reader->findString('lidTemperature'),
                    steps: $this->thermalCyclingConditionsSteps($reader->findList('step'))
                );
            });
    }

    private function thermalCyclingConditionsSteps(array $steps): StepCollection
    {
        return StepCollection::make($steps)
            ->filter(function (array $step) {
                return isset($step['temperature']);
            })
            ->map(function (array $step) {
                $reader = new ArrayReader($step);

                return new Step([
                    'number' => $reader->getInt('nr'),
                    'description' => $reader->findString('description'),
                    'temperature' => new Temperature([
                        'temperature' => $reader->findFloat('temperature.temperature'),
                        'duration' => $reader->findInt('temperature.duration'),
                    ]),
                ]);
            });
    }

    private function experiments(
        ArrayReader $data,
        SampleCollection $samples,
        ExperimenterCollection $experimenters,
        TargetCollection $targets
    ): ExperimentCollection {
        return ExperimentCollection::make($data->findList('experiment'))
            ->map(function (array $experiment) use ($targets, $samples, $experimenters) {
                $reader = new ArrayReader($experiment);

                return new Experiment([
                    'id' => $reader->getString('@attributes.id'),
                    'description' => $reader->findString('description'),
                    'runs' => RunCollection::make($reader->findList('run'))
                        ->map(function (array $run) use ($targets, $samples, $experimenters) {
                            $reader = new ArrayReader($run);

                            return new Run([
                                'id' => $reader->getString('@attributes.id'),
                                'description' => $reader->findString('description'),
                                'experimenter' => $experimenters->findById($reader->findString('experimenter.@attributes.id')),
                                'instrument' => $reader->findString('instrument'),
                                'dataCollectionSoftware' => new DataCollectionSoftware([
                                        'name' => $reader->getString('dataCollectionSoftware.name'),
                                        'version' => $reader->getString('dataCollectionSoftware.version'),
                                    ]
                                ),
                                'pcrFormat' => new PCRFormat([
                                        'rows' => $reader->getInt('pcrFormat.rows'),
                                        'columns' => $reader->getInt('pcrFormat.columns'),
                                        'rowLabel' => LabelFormat::create($reader->getString('pcrFormat.rowLabel')),
                                        'columnLabel' => LabelFormat::create($reader->getString('pcrFormat.columnLabel')),
                                    ]
                                ),
                                'reactions' => ReactionCollection::make($reader->findList('react'))
                                    ->map(function (array $reaction) use ($targets, $samples) {
                                        $reader = new ArrayReader($reaction);

                                        return new Reaction([
                                            'sample' => $samples->getById($reader->getString('sample.@attributes.id')),
                                            'data' => ReactionDataCollection::make($reader->findList('data'))
                                                ->map(function (array $reactionData) use ($targets) {
                                                    $reader = new ArrayReader($reactionData);

                                                    return new ReactionData([
                                                        'excluded' => $reader->getBoolean('excl'),
                                                        'target' => $targets->getById($reader->getString('tar.@attributes.id')),
                                                        'cq' => $reader->findFloat('cq'),
                                                        'amplificationDataPoints' => AmplificationDataCollection::make($reader->findList('adp'))
                                                            ->map(function (array $point) {
                                                                $reader = new ArrayReader($point);

                                                                return new AmplificationDataPoint([
                                                                    'cycle' => $reader->getFloat('cyc'),
                                                                    'temperature' => $reader->findFloat('tmp'),
                                                                    'fluor' => $reader->findFloat('fluor'),
                                                                ]);
                                                            }),
                                                        'meltingDataPoints' => MeltingCurveDataCollection::make($reader->findList('adp'))
                                                            ->map(function (array $point) {
                                                                $reader = new ArrayReader($point);

                                                                return new MeltingCurveDataPoint([
                                                                    'temperature' => $reader->getFloat('tmp'),
                                                                    'fluor' => $reader->getFloat('fluor'),
                                                                ]);
                                                            }),
                                                    ]);
                                                }),
                                        ]);
                                    }),
                            ]);
                        }),
                ]);
            });
    }
}
