<?php

namespace Domain\Assay\Jobs;

use App\Models\Assay;
use App\Models\Result;
use Domain\Assay\Mails\SendSampleReportsMail;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

final class CreateSampleReportsJob implements ShouldBeUnique, ShouldQueue
{
    use SerializesModels;

    public function __construct(private readonly Assay $assay, private readonly string $recipient)
    {
    }

    public function handle(): void
    {
        $recipient = $this->recipient;
        $assay = $this->assay;

        $chunks = $this->assay->load('results.sample')
            ->results
            ->chunk(200);
        $totalChunks = $chunks->count();
        $chunks->each(function (Collection $results, int $index) use ($totalChunks, $recipient, $assay) {
            Bus::batch(
                $results
                    ->map(fn (Result $result) => new AddSampleReportToArchive($result))
            )
                ->name("Create report for assay {$this->assay->name}:{$this->assay->id}")
                ->then(function (Batch $batch) use ($totalChunks, $index, $assay, $recipient) {
                    $zip = new ZipArchive();
                    $zipPath = Storage::path('tmp/'.$batch->id.'/reports.zip');
                    $zip->open(Storage::path('tmp/'.$batch->id.'/reports.zip'), ZipArchive::CREATE);

                    $assay
                        ->loadMissing('results.sample')
                        ->results
                        ->take(100)
                        ->each(fn (Result $result) => $zip->addFile(
                            Storage::path(AddSampleReportToArchive::getZipArchivePath($batch, $result->sample->identifier)),
                            $result->sample->identifier.'.pdf'
                        ));

                    $zip->close();
                    Mail::to($recipient)->send(new SendSampleReportsMail(
                        $assay->name,
                        $zipPath,
                        $index + 1,
                        $totalChunks
                    ));

                    Storage::disk('local')->deleteDirectory('tmp/'.$batch->id);
                })->dispatch();
        });

    }

    public function uniqueId(): string
    {
        return (string) $this->assay->id;
    }
}
