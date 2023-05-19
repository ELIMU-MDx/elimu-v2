<?php

declare(strict_types=1);

namespace Domain\Assay\Actions;

use App\Models\Assay;
use App\Models\User;
use Domain\Assay\Importers\AssayParameterExcelImporter;
use Illuminate\Database\Connection;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Excel;

final class CreateAssayFromFileAction
{
    public function __construct(private Connection $connection, private Excel $excel)
    {
    }

    public function execute(UploadedFile $file, User $user): Assay
    {
        return $this->connection->transaction(function () use ($file, $user) {
            $assay = Assay::create([
                'name' => explode('.', $file->getClientOriginalName(), 2)[0],
                'study_id' => $user->study_id,
                'user_id' => $user->id,
            ]);
            $this->excel->import(new AssayParameterExcelImporter($assay->id), $file->getRealPath());

            return $assay;
        });
    }
}
