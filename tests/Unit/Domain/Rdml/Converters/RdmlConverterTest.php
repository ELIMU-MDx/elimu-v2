<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Rdml\Converters;

use Domain\Rdml\Collections\AmplificationDataCollection;
use Domain\Rdml\Collections\DyeCollection;
use Domain\Rdml\Collections\ExperimentCollection;
use Domain\Rdml\Collections\ExperimenterCollection;
use Domain\Rdml\Collections\MeltingCurveDataCollection;
use Domain\Rdml\Collections\ReactionCollection;
use Domain\Rdml\Collections\ReactionDataCollection;
use Domain\Rdml\Collections\RunCollection;
use Domain\Rdml\Collections\SampleCollection;
use Domain\Rdml\Collections\TargetCollection;
use Domain\Rdml\Collections\ThermalCyclingConditionsCollection;
use Domain\Rdml\Converters\RdmlConverter;
use Domain\Rdml\DataTransferObjects\Dye;
use Domain\Rdml\DataTransferObjects\Experiment;
use Domain\Rdml\DataTransferObjects\Rdml;
use Domain\Rdml\DataTransferObjects\Reaction;
use Domain\Rdml\DataTransferObjects\ReactionData;
use Domain\Rdml\DataTransferObjects\Run;
use Domain\Rdml\DataTransferObjects\Sample;
use Domain\Rdml\DataTransferObjects\Target;
use PHPUnit\Framework\TestCase;

final class RdmlConverterTest extends TestCase
{
    /** @test */
    public function itConvertsAnRdml(): void
    {
        $converter = new RdmlConverter($this->rdml(
            new ExperimentCollection([
                new Experiment([
                    'id' => '1',
                    'runs' => new RunCollection([
                        new Run([
                            'id' => '1',
                            'reactions' => new ReactionCollection([
                                new Reaction([
                                    'sample' => new Sample([
                                        'id' => '1234',
                                        'type' => 'blood',
                                    ]),
                                    'data' => new ReactionDataCollection([
                                        new ReactionData([
                                            'target' => new Target([
                                                'id' => 'target-1',
                                                'type' => 'unkwn',
                                                'dye' => new Dye(['id' => 'abc']),
                                            ]),
                                            'cq' => 123,
                                            'amplificationDataPoints' => new AmplificationDataCollection(),
                                            'meltingDataPoints' => new MeltingCurveDataCollection(),
                                        ]),
                                    ]),
                                ]),
                            ]),
                        ]),
                        new Run([
                            'id' => '1',
                            'reactions' => new ReactionCollection([
                                new Reaction([
                                    'sample' => new Sample([
                                        'id' => '1234',
                                        'type' => 'blood',
                                    ]),
                                    'data' => new ReactionDataCollection([
                                        new ReactionData([
                                            'target' => new Target([
                                                'id' => 'target-1',
                                                'type' => 'unkwn',
                                                'dye' => new Dye(['id' => 'abc']),
                                            ]),
                                            'cq' => 123,
                                            'amplificationDataPoints' => new AmplificationDataCollection(),
                                            'meltingDataPoints' => new MeltingCurveDataCollection(),
                                        ]),
                                    ]),
                                ]),
                            ]),
                        ]),

                        new Run([
                            'id' => '1',
                            'reactions' => new ReactionCollection([
                                new Reaction([
                                    'sample' => new Sample([
                                        'id' => '1234',
                                        'type' => 'blood',
                                    ]),
                                    'data' => new ReactionDataCollection([
                                        new ReactionData([
                                            'target' => new Target([
                                                'id' => 'target-2',
                                                'type' => 'unkwn',
                                                'dye' => new Dye(['id' => 'abc']),
                                            ]),
                                            'cq' => 321,
                                            'amplificationDataPoints' => new AmplificationDataCollection(),
                                            'meltingDataPoints' => new MeltingCurveDataCollection(),
                                        ]),
                                    ]),
                                ]),
                            ]),
                        ]),
                    ]),
                ]),
            ]),
        ));

        $result = $converter->toSampleData();

        $this->assertCount(1, $result);
        $this->assertEquals(123, $result->first()->targets->first()->dataPoints->first()->cq);
        $this->assertEquals(321, $result->first()->targets->last()->dataPoints->first()->cq);
    }

    private function rdml(ExperimentCollection $experimentCollection): Rdml
    {
        return new Rdml([
            'version' => '1.1',
            'experimenter' => new ExperimenterCollection(),
            'dyes' => new DyeCollection(),
            'samples' => new SampleCollection(),
            'targets' => new TargetCollection(),
            'thermalCyclingConditions' => new ThermalCyclingConditionsCollection(),
            'experiments' => $experimentCollection,
        ]);
    }
}
