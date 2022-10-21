<?php

namespace Support;

use Livewire\Wireable;
use Spatie\LaravelData\Data as LaravelData;

class Data extends LaravelData implements Wireable
{
    public function toLivewire(): array
    {
        return $this->toArray();
    }

    public static function fromLivewire($value): static
    {
        return static::from($value);
    }
}
