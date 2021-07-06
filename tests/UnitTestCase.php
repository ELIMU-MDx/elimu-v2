<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class UnitTestCase extends PHPUnitTestCase
{
    public function resourcePath(string $path): string
    {
        return __DIR__.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.ltrim($path, '\/\\');
    }

}
