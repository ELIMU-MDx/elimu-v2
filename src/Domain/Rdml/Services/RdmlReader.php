<?php

declare(strict_types=1);

namespace Domain\Rdml\Services;

use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\DataTransferObjects\Rdml;
use Domain\Rdml\DataTransferObjects\QuantifyConfiguration;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\File\File;

final class RdmlReader
{
    public function __construct(
        private RdmlFileReader $fileReader,
        private RdmlParser $rdmlParser,
    ) {
    }

    public function read(File $file): Rdml
    {
        $rdml = $this->rdmlParser->extract($this->fileReader->read($file));

        if ($rdml->measurements->standards()->isEmpty()) {
            $rdml->quantifyConfigurations = collect();

            return $rdml;
        }

        $rdml->quantifyConfigurations = $this->quantifyConfigurationUsingStandards($rdml);

        return $rdml;
    }

    /**
     * @param  Rdml  $rdml
     * @return Collection<QuantifyConfiguration>
     */
    private function quantifyConfigurationUsingStandards(Rdml $rdml): Collection
    {
        return $rdml->measurements
            ->standards()
            ->groupBy('target')
            ->map(function (MeasurementCollection $measurements) {
                $regression = linear_regression(
                    collect($measurements)
                        ->map(fn(Measurement $measurement) => [
                            'x' => log10($measurement->quantity), 'y' => $measurement->cq,
                        ])
                        ->toArray()
                ))

                new QuantifyConfiguration(
                    [
                        'target' => $measurements->first()->target,
                        'slope' => round($regression['slope'], 2),
                        'intercept' => round($regression['intercept'], 2),
                    ],
                );
            })
            ->toBase();
    }
}
