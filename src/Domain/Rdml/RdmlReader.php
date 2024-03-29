<?php

declare(strict_types=1);

namespace Domain\Rdml;

use Domain\Rdml\Collections\StandardsCollection;
use Domain\Rdml\DataTransferObjects\QuantifyConfiguration;
use Domain\Rdml\DataTransferObjects\Rdml;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\File\File;

final class RdmlReader
{
    public function __construct(
        private readonly RdmlFileReader $fileReader,
        private readonly RdmlParser $rdmlParser,
    ) {
    }

    public function read(File $file): Rdml
    {
        $rdml = $this->rdmlParser->extract($this->fileReader->read($file));

        if ($rdml->measurements->standards()->nonNullDataPoints()->isEmpty()) {
            $rdml->quantifyConfigurations = collect();

            return $rdml;
        }

        $rdml->quantifyConfigurations = $this->quantifyConfigurationUsingStandards($rdml);

        return $rdml;
    }

    /**
     * @return Collection<QuantifyConfiguration>
     */
    private function quantifyConfigurationUsingStandards(Rdml $rdml): Collection
    {
        return $rdml->measurements
            ->standards()
            ->groupBy('target')
            ->filter(fn (StandardsCollection $standards) => $standards->nonNullDataPoints()->isNotEmpty())
            ->map(fn (StandardsCollection $standards) => $standards->quantifyConfiguration())
            ->values()
            ->toBase();
    }
}
