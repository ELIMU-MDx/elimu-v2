<?php

use App\Models\Experiment;
use Domain\Rdml\RdmlReader;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\HttpFoundation\File\File;

return new class extends Migration
{
    public function up(): void
    {
        $fileSystem = app(\Illuminate\Filesystem\FilesystemManager::class);
        Experiment::all()
            ->lazy()
            ->each(function (Experiment $experiment) use ($fileSystem) {
                $rdml = app(RdmlReader::class)
                    ->read(new File($fileSystem->disk()->path($experiment->rdml_path)));

                $experiment->instrument = $rdml->instrument;
                $experiment->saveQuietly();
            });
    }
};
