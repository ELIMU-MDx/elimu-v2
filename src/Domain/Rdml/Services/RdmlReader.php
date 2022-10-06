<?php

declare(strict_types=1);

namespace Domain\Rdml\Services;

use Domain\Rdml\Collections\StandardsCollection;
use Domain\Rdml\DataTransferObjects\QuantifyConfiguration;
use Domain\Rdml\DataTransferObjects\Rdml;
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
            ->map(fn (StandardsCollection $standards) => $standards->quantifyConfiguration())
            ->values()
            ->toBase();
    }
}
