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

final class DeleteOldAirdropFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        foreach (Storage::files('airdrop') as $file) {
            if (CarbonImmutable::createFromTimestamp(Storage::lastModified($file))->lessThan(now()->subDays(2))) {
                Storage::delete($file);
            }
        }
    }
}
