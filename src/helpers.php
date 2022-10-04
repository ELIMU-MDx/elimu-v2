<?php

function is_url(string $string): bool
{
    return Validator::make(
        ['url' => $string],
        ['url' => 'required|url']
    )->passes();
}

/**
 * @param  array{x: float|int, y: float|int}  $points
 * @return float[]|int[]
 */
function linear_regression(array $points)
{
    $x = array_map(fn ($point) => $point['x'], $points);
    $y = array_map(fn ($point) => $point['y'], $points);
    $n = count($x);     // number of items in the array
    $x_sum = array_sum($x); // sum of all X values
    $y_sum = array_sum($y); // sum of all Y values

    $xx_sum = 0;
    $xy_sum = 0;

    for ($i = 0; $i < $n; $i++) {
        $xy_sum += ($x[$i] * $y[$i]);
        $xx_sum += ($x[$i] * $x[$i]);
    }

    // Slope
    $slope = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));

    // calculate intercept
    $intercept = ($y_sum - ($slope * $x_sum)) / $n;

    return [
        'slope' => $slope,
        'intercept' => $intercept,
    ];
}
