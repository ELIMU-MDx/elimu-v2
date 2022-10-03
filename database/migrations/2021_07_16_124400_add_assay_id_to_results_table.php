<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->foreignId('assay_id')->after('sample_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->unique(['sample_id', 'assay_id', 'target']);
        });
    }
};
