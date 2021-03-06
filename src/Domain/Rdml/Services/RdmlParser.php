<?php

declare(strict_types=1);

namespace Domain\Rdml\Services;

use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\Collections\TargetCollection;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\DataTransferObjects\Rdml;
use Domain\Rdml\DataTransferObjects\Target;
use Domain\Rdml\Enums\MeasurementType;
use Domain\Rdml\LabelFormats\LabelFormatFactory;
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
        $controlIds = [];

        // TODO: refactor to collections
        foreach ($data->findList('sample') as $sample) {
            $sampleReader = new ArrayReader($sample);
            if (! in_array($sampleReader->findString('type'), ['pos', 'ntc'], true)) {
                continue;
            }

            $controlIds[$sampleReader->getString('@attributes.id')] = $sampleReader->getString('type');
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
                        'type' => MeasurementType::SAMPLE(),
                    ]);

                    $measurement->type = MeasurementType::byString($controlIds[$measurement->sample] ?? 'unkn');
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
            createdAt: $data->getDateTime('dateMade'),
            updatedAt: $data->getDateTime('dateUpdated'),
            targets: $targets,
            measurements: MeasurementCollection::make($measurements),
        );
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
}
