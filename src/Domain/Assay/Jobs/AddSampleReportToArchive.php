<?php

declare(strict_types=1);

namespace Domain\Assay\Jobs;

use App\Models\Result;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use URL;

final class AddSampleReportToArchive implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 10;

    public int $maxExceptions = 3;

    public function __construct(private Result $result)
    {

    }

    public function handle(): void
    {
        $batch = $this->batch();

        if (! $batch instanceof Batch) {
            return;
        }

        if ($batch->cancelled()) {
            return;
        }

        $path = self::getZipArchivePath($batch, $this->result->sample->identifier);

        ray(URL::temporarySignedRoute('samples.report',
            now()->addMinutes(5), [
                'sample' => $this->result->sample,
                'assay' => $this->result->assay,
            ]));

        Browsershot::url(URL::temporarySignedRoute('samples.report',
            now()->addMinutes(5), [
                'sample' => $this->result->sample,
                'assay' => $this->result->assay,
            ]))
            ->pages('1')
            ->emulateMedia('print')
            ->format('A4')
            ->save(Storage::path($path));
    }

    public static function getZipArchivePath(Batch $batch, string $sampleIdentifier): string
    {
        Storage::createDirectory('tmp/'.$batch->id);

        return 'tmp/'.$batch->id.'/'.$sampleIdentifier.'.pdf';
    }
}
