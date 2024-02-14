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
use Illuminate\Support\Facades\URL;
use Spatie\Browsershot\Browsershot;
use ZipArchive;

final class AddSampleReportToArchive implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tried = 10;

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
        $zip = new ZipArchive();
        if ($zip->open(self::getZipArchivePath($batch), ZipArchive::CREATE) !== true) {
            // zip might be locked by another job already
            $this->release(10);

            return;
        }

        $filename = $this->result->sample->identifier.'.pdf';
        $zip->addFromString(
            $this->result->sample->identifier.'.pdf',
            base64_decode(Browsershot::url(URL::temporarySignedRoute('samples.report',
                now()->addMinute(), [
                    'sample' => $this->result->sample,
                    'assay' => $this->result->assay,
                ]))
                ->pages('1')
                ->emulateMedia('print')
                ->base64pdf(),
            ));
        $zip->setCompressionName($filename, ZipArchive::CM_DEFLATE);
        $zip->close();
    }

    public static function getZipArchivePath(Batch $batch): string
    {
        Storage::createDirectory('tmp');

        return Storage::disk('local')->path('tmp/question-images-'.($batch->id).'.zip');
    }
}
