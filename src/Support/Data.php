<?php

namespace Support;

use Livewire\Wireable;
use Spatie\LaravelData\Concerns\TransformableData;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Contracts\TransformableData as TransformableDataContract;
use Spatie\LaravelData\Dto as LaravelData;

abstract class Data extends LaravelData implements TransformableDataContract, Wireable
{
    use TransformableData;
    use WireableData;
}
