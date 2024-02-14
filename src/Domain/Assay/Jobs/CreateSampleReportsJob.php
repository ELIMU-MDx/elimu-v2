<?php

namespace Domain\Assay\Jobs;

use App\Models\Assay;
use App\Models\Result;
use Domain\Assay\Mails\SendSampleReportsMail;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

final class CreateSampleReportsJob implements ShouldBeUnique, ShouldQueue
{
    use SerializesModels;

    public function __construct(private readonly Assay $assay, private readonly string $recipient)
    {
    }

    public function handle(): void
    {
        Bus::batch(
            $this->assay->load('results.sample')
                ->results
                ->map(fn (Result $result) => new AddSampleReportToArchive($result))
        )
            ->name("Create report for assay {$this->assay->name}:{$this->assay->id}")
            ->then(function (Batch $batch) {
                $path = AddSampleReportToArchive::getZipArchivePath($batch);
                Mail::to($this->recipient)->send(new SendSampleReportsMail($this->assay->name, AddSampleReportToArchive::getZipArchivePath($batch)));

                Storage::disk('local')->delete($path);
            })->dispatch();
    }

    public function uniqueId(): string
    {
        return (string) $this->assay->id;
    }
}
