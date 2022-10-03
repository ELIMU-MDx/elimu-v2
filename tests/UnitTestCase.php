<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class UnitTestCase extends PHPUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Model::unguard();
    }

    public function resourcePath(string $path): string
    {
        return __DIR__.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.ltrim($path, '\/\\');
    }
}
