<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Evaluation\Collections;

use Domain\Evaluation\Collections\DataPointCollection;
use Domain\Evaluation\DataTransferObjects\DataPoint;
use Domain\Evaluation\Enums\QualitativeResult;
use PHPUnit\Framework\TestCase;

final class DataPointCollectionTest extends TestCase
{
    /** @test */
    public function itCalculatesTheAverage(): void
    {
        $collection = $this->dataPoints(10.0, 18.0);

        $this->assertEquals(14.0, $collection->averageCq()->raw());
    }

    /** @test */
    public function itEvaluatesToPositive(): void
    {
        $collection = $this->dataPoints(10.0, 18.0);

        $this->assertEquals(QualitativeResult::POSITIVE(), $collection->qualify(20));
    }

    /** @test */
    public function itEvaluatesToNegative(): void
    {
        $collection = $this->dataPoints(10.0, 18.0);

        $this->assertEquals(QualitativeResult::NEGATIVE(), $collection->qualify(9));
    }

    /** @test */
    public function itQualifiesAResult(): void
    {
        $collection = $this->dataPoints(10.0, 18.0);

        $this->assertNotNull($collection->quantify(0.1, 10));
        $this->assertEquals(251188643150.96, $collection->quantify(0.1, 10));
    }

    private function dataPoints(?float ...$cqs): DataPointCollection
    {
        $collection = new DataPointCollection();

        foreach ($cqs as $cq) {
            $collection->add(new DataPoint(
                target: 'Foo',
                cq: $cq
            ));
        }

        return $collection;
    }
}
