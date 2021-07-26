<?php

declare(strict_types=1);

function is_url(string $string): bool
{
    return Validator::make(
        ['url' => $string],
        ['url' => 'required|url']
    )->passes();
}
