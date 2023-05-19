<?php

use Support\CustomCollection;
it('calculates the standard deviation', function () {
    $collection = new CustomCollection([
        25.51,
        25.28,
        24.98,
        24.40,
        24.28,
        24.27,
    ]);

    $standardDeviation = $collection->standardDeviation();

    $this->assertEquals(0.496, round($standardDeviation, 3));
});

it('calculates the standard deviation with callable', function () {
    $collection = new CustomCollection([
        new class
        {
            public float $cq = 25.51;
        },
    ]);

    $standardDeviation = $collection->standardDeviation(fn ($element) => $element->cq);

    $this->assertEquals(0, $standardDeviation);
});
