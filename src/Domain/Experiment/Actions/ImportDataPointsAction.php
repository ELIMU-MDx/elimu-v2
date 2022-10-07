<?php

namespace Domain\Experiment\Actions;

use Domain\Experiment\Enums\ImportStatus;
use Domain\Experiment\Models\DataPoint;
use Domain\Experiment\Models\Experiment;
use Domain\Experiment\Models\Measurement;
use Domain\Rdml\DataTransferObjects\AmplificationDataPoint;
use Domain\Rdml\DataTransferObjects\Measurement as MeasurementDTO;
use Domain\Rdml\Services\RdmlReader;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Filesystem\FilesystemManager;
use Symfony\Component\HttpFoundation\File\File;

final class ImportDataPointsAction
{
    public function __construct(
        private RdmlReader $rdmlReader,
        private Connection $connection,
        private FilesystemManager $filesystemManager,
    ) {
    }

    public function execute(Experiment $experiment)
    {
        activity()->disableLogging();
        $experiment->load('measurements');
        $experiment->import_status = ImportStatus::COMPLETED;
        DataPoint::whereHas('measurement', fn (Builder $builder) => $builder->where('experiment_id', $experiment->id))->delete();

        $rdml = $this->rdmlReader->read(new File($this->filesystemManager->disk()->path($experiment->rdml_path),
            false));

        $createdAt = now();
        $updatedAt = now();

        $dataPoints = new Collection($experiment->measurements->map(function (Measurement $measurement) use (
            $updatedAt,
            $createdAt,
            $rdml
        ) {
            $data = $rdml->measurements
                ->first(fn (MeasurementDTO $measurementDto
                ) => $measurementDto->is($measurement))?->amplificationDataPoints ?? collect();

            return
                $data->map(fn (AmplificationDataPoint $dataPoint) => [
                    'cycle' => $dataPoint->cycle,
                    'temperature' => round($dataPoint->temperature, 2),
                    'fluor' => round($dataPoint->fluor, 2),
                    'measurement_id' => $measurement->id,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);
        })->collapse());

        $this->connection->transaction(function () use ($experiment, $dataPoints) {
            DataPoint::insert($dataPoints->toArray());
            $experiment->save();
        });
        activity()->enableLogging();
    }
}
