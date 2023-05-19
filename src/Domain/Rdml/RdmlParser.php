<?php

declare(strict_types=1);

namespace Domain\Rdml;

use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\Collections\TargetCollection;
use Domain\Rdml\DataTransferObjects\AmplificationDataPoint;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\DataTransferObjects\Rdml;
use Domain\Rdml\DataTransferObjects\Target;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Rdml\LabelFormats\LabelFormatFactory;
use Illuminate\Support\Arr;
use RuntimeException;
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
        $measurements = [];
        $nonSampleIds = [];
        $definedSampleIds = [];

        // TODO: refactor to collections
        foreach ($data->findList('sample') as $sample) {
            $sampleReader = new ArrayReader($sample);
            $type = $sampleReader->findString('type');
            $definedSampleIds[] = $sampleReader->findString('@attributes.id');
            // fix since neg control does not seem to work with type
            if ($sampleReader->findString('@attributes.id') === 'neg' && $type === 'unkn') {
                $type = 'neg';
            }

            if (! in_array($sampleReader->findString('type'), ['pos', 'ntc', 'std', 'neg'],
                true) && $sampleReader->findString('')) {
                continue;
            }

            $nonSampleIds[$sampleReader->getString('@attributes.id')] = [
                'type' => $type,
                'quantity' => $sampleReader->findFloat('quantity.value'),
            ];
        }

        foreach ($data->findList('experiment') as $experiment) {
            $experimentReader = new ArrayReader($experiment);
            $experimentId = $experimentReader->getString('@attributes.id');

            foreach ($experimentReader->findList('run') as $run) {
                $runReader = new ArrayReader($run);
                $runId = $runReader->getString('@attributes.id');
                $pcrFormat = $runReader->find('pcrFormat') ?? throw new RuntimeException('No pcr format set');

                foreach ($runReader->findList('react') as $reaction) {
                    $reactionReader = new ArrayReader($reaction);
                    // ignore samples which have no defined type
                    if (! in_array($reactionReader->getString('sample.@attributes.id'), $definedSampleIds, true)) {
                        continue;
                    }
                    $reactionId = $reactionReader->getString('@attributes.id');
                    $measurement = new Measurement([
                        'experiment' => $experimentId,
                        'run' => $runId,
                        'sample' => $reactionReader->getString('sample.@attributes.id'),
                        'target' => $reactionReader->getString('data.tar.@attributes.id'),
                        'position' => $this->getPosition($pcrFormat, $reactionId),
                        'excluded' => $reactionReader->getBoolean('data.excl'),
                        'cq' => $reactionReader->has('data.cq')
                            ? $reactionReader->findFloat('data.cq')
                            : $this->calculateCq($reactionReader->find('data.adp.*.fluor', [])),
                        'amplificationDataPoints' => collect($reactionReader->find('data.adp', []))
                            ->map(fn (array $adp) => new AmplificationDataPoint([
                                'cycle' => $adp['cyc'],
                                'temperature' => $adp['tmp'] ?? null,
                                'fluor' => $adp['fluor'] ?? null,
                            ])),
                        'type' => MeasurementType::SAMPLE(),
                    ]);

                    if ($this->alreadyExists($measurements, $measurement)) {
                        continue;
                    }

                    $measurement->quantity = $nonSampleIds[$measurement->sample]['quantity'] ?? null;
                    $measurement->type = MeasurementType::byString($nonSampleIds[$measurement->sample]['type'] ?? 'unkn');

                    if (MeasurementType::isControl($measurement->type) && $measurement->cq !== null && $measurement->cq < 5) {
                        // ignore controls with a smaller than 5 cq
                        continue;
                    }
                    $measurements[] = $measurement;
                }
            }
        }
        $targets = TargetCollection::make($data->findList('target'))
            ->map(function (array $target) {
                $targetReader = new ArrayReader($target);

                return new Target([
                    'id' => $targetReader->getString('@attributes.id'),
                    'type' => $targetReader->getString('type'),
                    'dye' => $targetReader->getString('dyeId.@attributes.id'),
                ]);
            });

        return new Rdml(
            version: $data->getString('@attributes.version', '1.1'),
            instrument: $this->findInstrument($data),
            createdAt: $data->getDateTime('dateMade'),
            updatedAt: $data->getDateTime('dateUpdated'),
            targets: $targets,
            measurements: MeasurementCollection::make($measurements),
            quantifyConfigurations: collect()
        );
    }

    private function findInstrument(ArrayReader $data): ?string
    {
        $instrument = $data->find('experiment.run.0.instrument', '');

        if (! preg_match('/SN: (\w+)/', $instrument, $matches)) {
            return null;
        }

        return $matches[1];
    }

    /**
     * @throws \JsonException
     */
    private function xmlToArray(string $xml): ArrayReader
    {
        $data = json_decode(json_encode(
            simplexml_load_string($xml),
            JSON_THROW_ON_ERROR
        ), true, 512, JSON_THROW_ON_ERROR);

        return new ArrayReader($data);
    }

    private function calculateCq(array $dataPoints): ?float
    {
        return null;
    }

    private function getPosition(array $pcrFormat, string $position): string
    {
        if (empty($pcrFormat)) {
            return $position;
        }

        $format = new ArrayReader($pcrFormat);

        $rows = $format->getInt('rows');

        if ($rows === -1) {
            return (string) ($rows * ($format->findInt('columns') ?: 1));
        }
        if ($rows === 1) {
            return '';
        }

        $columns = $format->getInt('columns');
        $rowFormat = LabelFormatFactory::get($format->getString('rowLabel'));
        $columnFormat = LabelFormatFactory::get($format->getString('columnLabel'));

        $column = $position % $columns;
        $column = $column === 0 ? $columns : $column;
        $row = (($position - $column) / $columns) + 1;

        return $rowFormat->getPosition($row).$columnFormat->getPosition($column);
    }

    /**
     * @param  Measurement[]  $measurements
     * @param  Measurement  $measurement
     * @return bool
     */
    private function alreadyExists(array $measurements, Measurement $measurement): bool
    {
        $existingMeasurement = Arr::first(
            $measurements,
            fn (Measurement $current) => $current->cq === $measurement->cq
                && $current->target === $measurement->target
                && $current->position === $measurement->position
                && $current->sample === $measurement->sample
        );

        if (! $existingMeasurement) {
            return false;
        }

        if ($existingMeasurement->amplificationDataPoints->isEmpty()) {
            $existingMeasurement->amplificationDataPoints = $measurement->amplificationDataPoints;
        }

        return true;
    }
}
