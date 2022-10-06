<?php

use Domain\Experiment\Models\Experiment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Experiment::query()
            ->whereHas('quantifyParameters')
            ->delete();
        Schema::table('quantify_parameters', function (Blueprint $table) {
            $table->decimal('correlation_coefficient', 8, 4)->after('intercept');
        });
    }
};
