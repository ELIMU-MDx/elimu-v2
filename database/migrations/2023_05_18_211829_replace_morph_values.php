<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('personal_access_tokens')
            ->where('tokenable_type', 'Domain\\Users\\Models\\User')
            ->update(['tokenable_type' => 'user']);

        DB::table('activity_log')
            ->where('subject_type', 'Domain\\Assay\\Models\\Assay')
            ->update(['subject_type' => 'assay']);

        DB::table('activity_log')
            ->where('subject_type', 'Domain\\Experiment\\Models\\Experiment')
            ->update(['subject_type' => 'experiment']);

        DB::table('activity_log')
            ->where('subject_type', 'Domain\\Experiment\\Models\Measurement')
            ->update(['subject_type' => 'measurement']);

        DB::table('activity_log')
            ->where('causer_type', 'Domain\\Users\\Models\\User')
            ->update(['causer_type' => 'user']);

    }
};
