<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assay_parameters', function (Blueprint $table) {
            $table->decimal('cutoff')->change();
            $table->decimal('standard_deviation_cutoff')->change();
            $table->decimal('slope')->nullable()->change();
            $table->decimal('intercept')->nullable()->change();
        });

        Schema::table('results', function (Blueprint $table) {
            $table->decimal('cq')->nullable()->change();
            $table->decimal('quantification')->nullable()->change();
            $table->decimal('standard_deviation')->nullable()->change();
        });

        Schema::table('measurements', function (Blueprint $table) {
            $table->decimal('cq')->nullable()->change();
        });
    }
};
