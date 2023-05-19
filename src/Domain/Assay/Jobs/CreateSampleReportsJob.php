<?php

namespace Domain\Assay\Jobs;

use App\Models\Assay;
use App\Models\Result;
use Domain\Assay\Mails\SendSampleReportsMail;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Spatie\Browsershot\Browsershot;
use ZipArchive;

final class CreateSampleReportsJob implements ShouldQueue, ShouldBeUnique
{
    public function __construct(private readonly Assay $assay, private readonly string $recipient)
    {
    }

    public function handle(): void
    {
        $file = @tempnam('tmp', 'zip');

        $zip = new ZipArchive();
        $zip->open($file, ZipArchive::OVERWRITE);

        $this->assay->load('results.sample')->results->each(function (Result $result) use ($zip) {
            $zip->addFromString($result->sample->identifier.'.pdf',
                base64_decode(Browsershot::url(URL::temporarySignedRoute('samples.report',
                    now()->addMinute(), [
                        'sample' => $result->sample,
                        'assay' => $result->assay,
                    ]))->pages('1')->emulateMedia('print')->base64pdf()));
        });

        $zip->close();
        Mail::to($this->recipient)->send(new SendSampleReportsMail($this->assay->name, $file));
        unlink($file);
    }

    public function uniqueId(): string
    {
        return (string) $this->assay->id;
    }
}
