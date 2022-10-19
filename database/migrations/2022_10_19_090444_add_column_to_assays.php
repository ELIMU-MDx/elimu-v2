<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assay_parameters', function (Blueprint $table) {
            $table->decimal('coefficient_of_variation_cutoff', 8, 3)->after('standard_deviation_cutoff')->nullable();
        });
        Schema::table('assay_parameters', function (Blueprint $table) {
            $table->decimal('standard_deviation_cutoff')->nullable()->change();
        });
    }
};
