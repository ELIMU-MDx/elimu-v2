<?php

declare(strict_types=1);

namespace Support;

use ArrayIterator;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use IteratorAggregate;
use OutOfBoundsException;
use Traversable;

final class ArrayReader implements IteratorAggregate
{
    public function __construct(private readonly array $data)
    {
    }

    public function getBoolean(string $key): bool
    {
        return (bool) $this->find($key);
    }

    public function find(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->data, $key, $default);
    }

    public function getString(string $key, string $default = null): string
    {
        $value = $this->findString($key, $default);

        if ($value === null) {
            throw new OutOfBoundsException("Could not find value for key {$key}");
        }

        return $value;
    }

    public function findString(string $key, string $default = null): ?string
    {
        $value = $this->find($key);

        if (! is_string($value)) {
            return $default;
        }

        return $value;
    }

    public function getInt(string $key, int $default = null): int
    {
        $value = $this->findInt($key, $default);

        if ($value === null) {
            throw new OutOfBoundsException("Could not find value for key {$key}");
        }

        return $value;
    }

    public function findInt(string $key, int $default = null): ?int
    {
        $value = $this->find($key);

        if (! is_numeric($value)) {
            return $default;
        }

        return (int) $value;
    }

    public function getFloat(string $key, float $default = null): float
    {
        $value = $this->findFloat($key, $default);

        if ($value === null) {
            throw new OutOfBoundsException("Could not find value for key {$key}");
        }

        return $value;
    }

    public function findFloat(string $key, float $default = null): ?float
    {
        $value = $this->find($key);

        if (! is_numeric($value)) {
            return $default;
        }

        return (float) $value;
    }

    public function getDateTime(string $key, CarbonImmutable $default = null): CarbonImmutable
    {
        $value = $this->findDateTime($key, $default);

        if ($value === null) {
            throw new OutOfBoundsException("Could not find value for key {$key}");
        }

        return $value;
    }

    public function findDateTime(string $key, CarbonImmutable $default = null): ?CarbonImmutable
    {
        $value = $this->find($key);

        if (! $value) {
            return $default;
        }

        return CarbonImmutable::parse($value);
    }

    public function findList(string $key): ArrayReader
    {
        $value = $this->find($key);

        if (! is_array($value)) {
            return new ArrayReader([]);
        }

        if (! isset($value[0])) {
            return new ArrayReader([$value]);
        }

        return new ArrayReader($value);
    }

    public function has(string $key): bool
    {
        return Arr::has($this->data, $key);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }
}
