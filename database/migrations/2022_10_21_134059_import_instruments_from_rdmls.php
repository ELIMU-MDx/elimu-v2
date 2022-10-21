<?php

use Domain\Experiment\Models\Experiment;
use Domain\Rdml\Services\RdmlReader;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\File\File;

return new class extends Migration
{
    public function up(): void
    {
        $fileSystem = app(\Illuminate\Filesystem\FilesystemManager::class);
        Experiment::all()
            ->lazy()
            ->each(function(Experiment $experiment) use ($fileSystem) {
                $rdml = app(RdmlReader::class)
                    ->read(new File($fileSystem->disk()->path($experiment->rdml_path)));

                $experiment->instrument = $rdml->instrument;
                $experiment->saveQuietly();
            });
    }
};
