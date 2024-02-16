<?php

namespace Domain\Assay\Jobs;

use App\Models\Assay;
use App\Models\Result;
use Carbon\CarbonInterval;
use Domain\Assay\Mails\SendSampleReportsMail;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;
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
            ->chunk(300);
        $totalChunks = $chunks->count();
        $lock = Cache::lock('create-sample-reports-'.$assay->id.'-'.md5($recipient), (int) CarbonInterval::hour()->totalSeconds);

        try {
            if (! $lock->get()) {
                return;
            }
            Bus::chain([
                ...$chunks->map(function (Collection $results, int $index) use ($totalChunks, $recipient, $assay) {
                    $current = $index + 1;
                    $sampleIds = $results->map(fn (Result $result) => $result->sample->identifier)->toBase();
                    $assayName = $assay->name;

                    return Bus::batch(
                        $results
                            ->toBase()
                            ->map(fn (Result $result) => new AddSampleReportToArchive($result)),
                    )
                        ->name("Create report for assay {$assay->name}:{$assay->id} ({$current}/{$totalChunks})")
                        ->then(function (Batch $batch) use ($assayName, $sampleIds, $totalChunks, $index, $recipient) {
                            $zip = new ZipArchive();
                            $zipPath = Storage::path('tmp/'.$batch->id.'/reports.zip');
                            $zip->open(Storage::path('tmp/'.$batch->id.'/reports.zip'), ZipArchive::CREATE);

                            $sampleIds
                                ->each(fn (string $sampleIdentifier) => $zip->addFile(
                                    Storage::path(AddSampleReportToArchive::getZipArchivePath($batch, $sampleIdentifier)),
                                    $sampleIdentifier.'.pdf',
                                ));

                            $zip->close();
                            Mail::to($recipient)->send(new SendSampleReportsMail(
                                $assayName,
                                $zipPath,
                                $index + 1,
                                $totalChunks,
                            ));

                            Storage::disk('local')->deleteDirectory('tmp/'.$batch->id);
                        });
                })->all(),
                function () use ($lock) {
                    $lock->release();
                },
            ])->catch(function (Throwable $e) use ($lock) {
                $lock->release();
                report($e);
            })->dispatch();

        } catch (Throwable $e) {
            $lock->release();
            throw $e;
        }

    }

    public function uniqueId(): string
    {
        return (string) $this->assay->id;
    }
}
