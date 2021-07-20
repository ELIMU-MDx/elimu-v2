<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Rdml\Converters;

use Domain\Rdml\Collections\MeasurementCollection;
use Domain\Rdml\Collections\TargetCollection;
use Domain\Rdml\Converters\RdmlConverter;
use Domain\Rdml\DataTransferObjects\Measurement;
use Domain\Rdml\DataTransferObjects\Rdml;
use Tests\UnitTestCase;

final class RdmlConverterTest extends UnitTestCase
{
    /** @test
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function itConvertsAnRdml(): void
    {
        $converter = new RdmlConverter(
            $this->rdml([
                [
                    'experiment' => '12',
                    'run' => '12',
                    'sample' => 'sample-xy',
                    'target' => 'target-a',
                    'position' => 'A1',
                    'excluded' => false,
                    'cq' => 123,
                ],
                [
                    'experiment' => '12',
                    'run' => '12',
                    'sample' => 'sample-xy',
                    'target' => 'target-b',
                    'position' => 'A1',
                    'excluded' => false,
                    'cq' => 321,
                ],
            ])
        );

        $result = $converter->toSampleData();

        $this->assertCount(1, $result);
        $this->assertEquals(123, $result->first()->targets->first()->dataPoints->first()->cq);
        $this->assertEquals(321, $result->first()->targets->last()->dataPoints->first()->cq);
    }

    /** @test */
    public function itConvertsARdmlToAnExperiment(): void
    {
        $converter = new RdmlConverter($this->rdml([
            [
                'experiment' => '12',
                'run' => '12',
                'sample' => 'sample-xy',
                'target' => 'target-a',
                'position' => 'A1',
                'excluded' => false,
                'cq' => 123,
            ],
            [
                'experiment' => '12',
                'run' => '12',
                'sample' => 'sample-xy',
                'target' => 'target-b',
                'position' => 'A2',
                'excluded' => false,
                'cq' => 321,
            ],
            [
                'experiment' => '12',
                'run' => '12',
                'sample' => 'sample-xy',
                'target' => 'target-b',
                'position' => 'A3',
                'excluded' => false,
                'cq' => 321,
            ],
        ]));

        $measurements = $converter->toMeasurements();

        $this->assertCount(3, $measurements);
        $this->assertEquals(123, $measurements->first()->cq);
        $this->assertSame($measurements->first()->sample, $measurements->last()->sample);
    }

    /**
     * @param  array  $measurements
     * @return \Domain\Rdml\DataTransferObjects\Rdml
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    private function rdml(array $measurements): Rdml
    {
        return new Rdml([
            'version' => '1.1',
            'targets' => new TargetCollection(),
            'measurements' => MeasurementCollection::make($measurements)
                ->map(function (array $measurement) {
                    return new Measurement($measurement);
                }),
        ]);
    }
}
