<?php

declare(strict_types=1);

namespace App\Jobs;

use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

final class DeleteCachedSampleReportsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        foreach (Storage::directories('tmp') as $directory) {
            if (CarbonImmutable::createFromTimestamp(Storage::lastModified($directory))->lessThan(now()->subHours(4))) {
                Storage::deleteDirectory($directory);
            }
        }
    }
}
